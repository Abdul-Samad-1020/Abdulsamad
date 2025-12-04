
php
<?php
require_once '../config.php';
require_role('admin');
$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $mysqli->real_escape_string($_POST['course_code']);
    $title = $mysqli->real_escape_string($_POST['course_title']);
    $credits = floatval($_POST['credit_hours']);
    $mysqli->query("INSERT INTO courses (course_code, course_title, credit_hours) VALUES ('$code','$title',$credits)");
    $msg = 'Course added';
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Course</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Add Course</h2>
    <?php if ($msg) echo '<div class="success">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="post">
      <label>Course Code<br><input name="course_code" required></label><br>
      <label>Course Title<br><input name="course_title" required></label><br>
      <label>Credit Hours<br><input name="credit_hours" type="number" step="0.25" value="3.00" required></label><br>
      <button type="submit">Add</button>
    </form>
    <p><a href="courses.php">Back</a></p>
  </div>
</body>
</html>
