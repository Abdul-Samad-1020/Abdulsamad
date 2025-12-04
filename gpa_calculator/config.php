php
<?php
// config.php - database config and helper functions
session_start();

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'gpa_calculator';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB connection failed: ' . $mysqli->connect_error);
}

// password helper (use password_hash when creating users)
function hash_password($plain) {
    return password_hash($plain, PASSWORD_DEFAULT);
}
function verify_pass($plain, $hash) {
    return password_verify($plain, $hash);
}

// grade mapping: marks -> letter & points (customize as required)
function map_marks_to_grade($marks) {
    $m = floatval($marks);
    if ($m >= 90) return ['A+', 4.00];
    if ($m >= 85) return ['A', 4.00];
    if ($m >= 80) return ['A-', 3.67];
    if ($m >= 75) return ['B+', 3.33];
    if ($m >= 70) return ['B', 3.00];
    if ($m >= 65) return ['B-', 2.67];
    if ($m >= 60) return ['C+', 2.33];
    if ($m >= 55) return ['C', 2.00];
    if ($m >= 50) return ['D', 1.00];
    return ['F', 0.00];
}

// GPA calculation: takes array of ['credit_hours'=>x,'grade_point'=>y]
function calculate_gpa($items) {
    $total_credit = 0.0; $weighted = 0.0;
    foreach ($items as $it) {
        $ch = floatval($it['credit_hours']);
        $gp = floatval($it['grade_point']);
        $total_credit += $ch;
        $weighted += ($ch * $gp);
    }
    if ($total_credit == 0) return 0.0;
    return round($weighted / $total_credit, 2);
}

// require login helpers
function require_role($role) {
    if (!isset($_SESSION['user'])) {
        header('Location: /index.php'); exit;
    }
    if ($_SESSION['user']['role'] !== $role) {
        die('Access denied');
    }
}

?>
