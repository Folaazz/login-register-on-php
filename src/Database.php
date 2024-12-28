<?php
class Database {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function saveMessage($from, $to, $message) {
        $stmt = $this->connection->prepare("INSERT INTO messages (from_user, to_user, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $from, $to, $message);
        $stmt->execute();
        $stmt->close();
    }

    public function getMessages($from, $to) {
        $stmt = $this->connection->prepare("SELECT message FROM messages WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?) ORDER BY id ASC");
        $stmt->bind_param("ssss", $from, $to, $to, $from);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row['message'];
        }
        $stmt->close();
        return $messages;
    }
    public function registerUser ($username, $password, $alias, $email) {
        $stmt = $this->connection->prepare("INSERT INTO users (username, password, alias, email) VALUES (?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $hashedPassword, $alias, $email);
        $stmt->execute();
        $stmt->close();
    }
    
    public function getUser ($username, $password) {
        $stmt = $this->connection->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {  
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                return true; // Пользователь найден
            }
        }
        return false; // Пользователь не найден
    }
    public function getAllUsers() {
        $stmt = $this->connection->prepare("SELECT username, alias FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }
    public function checkUserExists($username, $email, $alias) {
        $stmt = $this->connection->prepare("SELECT username, email, alias FROM users WHERE username = ? OR email = ? OR alias = ?");
        $stmt->bind_param("sss", $username, $email, $alias);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $existingFields = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] === $username) {
                $existingFields[] = 'именем';
            }
            if ($row['email'] === $email) {
                $existingFields[] = 'почтой';
            }
            if ($row['alias'] === $alias) {
                $existingFields[] = 'псевдонимом';
            }
        }
    
        return $existingFields; // Возвращает массив с занятыми полями
    }
}
?>