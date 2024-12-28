<?php
session_start();
require_once '../conf/config.php';
require_once '../src/Database.php';

if (isset($_SESSION['registration_success'])) {
    $headerText = "Теперь можно войти";
    unset($_SESSION['registration_success']);
} else {
    $headerText = "Login";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();
    $user = $db->getUser ($username, $password);

    if ($user) {
        $_SESSION['username'] = $username;
        header("Location: caht2.php");
        exit();
    } else {
        $error = "Неверное имя пользователя или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">  
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="apog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login">
        <div class="form">
            <form method="POST">
                <h2><?php echo $headerText; ?></h2>
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
                    <input type="text" placeholder="Username" name="username" required>
                    <div class="password-container">
                        <input type="password" placeholder="Password" name="password" id="password" required>
                        <span class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="warring">
                    <h2>Внимание</h2>
                    <p>Входя в аккаунт, вы соглашаетесь с</p>
                    <a href="#">нашей политикой</a>
                </div>
                <input type="submit" value="Войти" class="submit">
                <a href="register.php" class="to_register">Еще нет аккаунта?</a>
            </form>
        </div>
    </div>
    <?php if (isset($error)) echo "<div class='err_message'><p>$error</p></div>"; ?>
    <script src="js.js"></script>
</body>
</html>