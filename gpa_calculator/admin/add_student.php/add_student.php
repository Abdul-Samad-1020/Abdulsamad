php
<?php
require_once '../config.php';
require_role('admin');
$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_number = $mysqli->real_escape_string($_POST['student_number']);
    $full_name = $mysqli->real_escape_string($_POST['full_name']);
    $program = $mysqli->real_escape_string($_POST['program']);
    $semester = intval($_POST['semester']);

    $mysqli->query("INSERT INTO students (student_number, full_name, program, semester) VALUES ('$student_number','$full_name','$program',$semester)");
    $sid = $mysqli->insert_id;
    // create a user account for student with default password
    $default_pass = password_hash('student123', PASSWORD_DEFAULT);
    $username = strtolower(preg_replace('/\s+/','',explode(' ', $full_name)[0]).$sid);
    $mysqli->query("INSERT INTO users (username,password,role,student_id) VALUES ('".$mysqli->real_escape_string($username)."','$default_pass','student',$sid)");
    $msg = "Student added. Username: $username , password: student123";
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Student</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Add Student</h2>
    <?php if ($msg) echo '<div class="success">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="post">
      <label>Student Number<br><input name="student_number" required></label><br>
      <label>Full Name<br><input name="full_name" required></label><br>
      <label>Program<br><input name="program"></label><br>
      <label>Semester<br><input name="semester" type="number" min="1" value="1"></label><br>
      <button type="submit">Add</button>
    </form>
    <p><a href="students.php">Back</a></p>
  </div>
</body>
</html>

