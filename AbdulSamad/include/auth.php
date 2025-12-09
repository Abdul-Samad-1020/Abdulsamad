

<?php
session_start();
require_once '../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

function register($username, $email, $password) {
    global $pdo;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed]);
    } catch(PDOException $e) {
        return false;
    }
}

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}
?>
```

