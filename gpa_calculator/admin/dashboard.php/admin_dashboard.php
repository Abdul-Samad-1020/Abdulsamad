php
<?php
require_once '../config.php';
require_role('admin');

// quick metrics
$students_count = $mysqli->query('SELECT COUNT(*) as c FROM students')->fetch_assoc()['c'];
courses_count = $mysqli->query('SELECT COUNT(*) as c FROM courses')->fetch_assoc()['c'];
$grades_count = $mysqli->query('SELECT COUNT(*) as c FROM grades')->fetch_assoc()['c'];

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?=htmlspecialchars($_SESSION['user']['username'])?></p>
    <nav>
      <a href="students.php">Students</a> | <a href="courses.php">Courses</a> | <a href="grades.php">Grades</a> | <a href="../logout.php">Logout</a>
    </nav>
    <section>
      <ul>
        <li>Total students: <?=$students_count?></li>
        <li>Total courses: <?=$courses_count?></li>
        <li>Total grade entries: <?=$grades_count?></li>
      </ul>
    </section>
  </div>
</body>
</html>

