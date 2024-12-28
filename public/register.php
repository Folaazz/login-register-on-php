<?php
session_start();
require_once '../conf/config.php';
require_once '../src/Database.php';

$error = ''; // Переменная для хранения ошибок
$usernameError = '';
$passwordError = '';
$aliasError = '';
$emailError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $alias = $_POST['alias'];
    $email = $_POST['email'];

    $db = new Database();

    // Проверка на существование пользователя
    $existingFields = $db->checkUserExists($username, $email, $alias);
    if (in_array('именем', $existingFields)) {
        $usernameError = 'Имя пользователя занято.';
    }
    if (in_array('почтой', $existingFields)) {
        $emailError = 'Email уже используется.';
    }
    if (in_array('псевдонимом', $existingFields)) {
        $aliasError = 'Псевдоним уже занят.';
    }

    if (empty($usernameError) && empty($emailError) && empty($aliasError)) {
        try {
            // Регистрация пользователя в базе данных
            $db->registerUser ($username, $password, $alias, $email);
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            // Обработка ошибки дублирования
            if ($e->getCode() == 1062) { // Код ошибки для дублирования
                $usernameError = 'Имя пользователя занято.';
            } else {
                $error = 'Произошла ошибка: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="apog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login">
        <div class="form">
            <form method="POST">
                <h2>Register</h2>
                <div class="line-container">
                <div class="line-container-with">
                    <hr class="line">
                    <span class="line-text">with</span>
                    <hr class="line">
                </div>
                <div class="social-icons">
                    <a href="#" target="_blank" class="icon"><i class="fab fa-google"></i></a>
                    <a href="#" target="_blank" class="icon"><i class="fab fa-yandex"></i></a>
                </div>
                <div class="line-container-or">
                    <hr class="line">
                    <span class="line-text">or</span>
                    <hr class="line">
                </div>
                <div class="form-input">
                    <input type="text" name="username" placeholder="Username"  autocomplete="off" required>
                    <span class="error"><?php echo htmlspecialchars($usernameError); ?></span>
                    
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Password"  autocomplete="new-password" required>
                        <span class="reg-toggle-password" id="togglePassword">
                            <i class="fas fa-eye-slash" id="reg-i"></i>
                        </span>
                    </div>
                    <span class="error"><?php echo htmlspecialchars($passwordError); ?></span>
                    
                    <input type="text" name="alias" placeholder="Alias"  autocomplete="off" required>
                    <span class="error"><?php echo htmlspecialchars($aliasError); ?></span>
                    
                    <input type="email" name="email" placeholder="Email"  autocomplete="off" required>
                    <span class="error"><?php echo htmlspecialchars($emailError); ?></span>
                </div>
                <input type="submit" value="Войти" class="reg-submit">
                <a href="index.php" class="to_register">Уже есть аккаунт?</a>
            </form>
        </div>
    </div>
<script src="js.js"></script>
</body>
</html>