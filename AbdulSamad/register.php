
<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if ($password !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed]);
            $success = 'Registration successful! You can now login.';
        } catch(PDOException $e) {
            $error = 'Email or username already exists';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CGPA Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-8 w-full max-w-md border border-white/20">
        <h1 class="text-3xl font-bold text-white text-center mb-6">Create Account</h1>
        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-400 text-white px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="bg-green-500/20 border border-green-400 text-white px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-white mb-2">Username</label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
            <div>
                <label class="block text-white mb-2">Email</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
            <div>
                <label class="block text-white mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
            <div>
                <label class="block text-white mb-2">Confirm Password</label>
                <input type="password" name="confirm_password" required 
                    class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
            </div>
            <button type="submit" 
                class="w-full bg-white text-purple-600 font-semibold py-2 rounded-lg hover:bg-white/90 transition">
                Register
            </button>
        </form>
        <p class="text-white text-center mt-6">
            Already have an account? <a href="index.php" class="underline font-semibold">Login</a>
        </p>
    </div>
</body>
</html>

