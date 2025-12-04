
<?php
require_once 'config.php';

// if already logged in
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') header('Location: admin/dashboard.php');
    else header('Location: student/dashboard.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare('SELECT id, username, password, role, student_id FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        // if password stored empty (seed), set it now
        if (empty($row['password'])) {
            // for seeded accounts set to default same as username + 123 (example)
            $newpass = password_hash('admin123', PASSWORD_DEFAULT);
            $u = $row['username'];
            $update = $mysqli->prepare('UPDATE users SET password=? WHERE id=?');
            $update->bind_param('si', $newpass, $row['id']);
            $update->execute();
            $row['password'] = $newpass;
        }

        if (password_verify($password, $row['password'])) {
            // set session
            $_SESSION['user'] = ['id' => $row['id'], 'username' => $row['username'], 'role' => $row['role'], 'student_id' => $row['student_id']];
            if ($row['role'] === 'admin') header('Location: admin/dashboard.php');
            else header('Location: student/dashboard.php');
            exit;
        } else {
            $err = 'Invalid credentials';
        }
    } else {
        $err = 'Invalid credentials';
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>GPA Calculator - Login</title>
  <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
  <div class="container center">
    <h1>GPA Calculator</h1>
    <?php if ($err): ?>
      <div class="error"><?=htmlspecialchars($err)?></div>
    <?php endif; ?>
    <form method="post">
      <label>Username<br><input name="username" required></label><br>
      <label>Password<br><input name="password" type="password" required></label><br>
      <button type="submit">Login</button>
    </form>
    <p>Default admin (username: <strong>admin</strong> password: <strong>admin123</strong>) if you imported seed data.</p>
  </div>
</body>
</html>
