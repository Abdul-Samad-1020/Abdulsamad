php
<?php
require_once '../config.php';
require_role('admin');
$courses = $mysqli->query('SELECT * FROM courses ORDER BY created_at DESC');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Courses</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Courses</h2>
    <a href="add_course.php">+ Add Course</a> | <a href="dashboard.php">Back</a>
    <table class="table">
      <thead><tr><th>#</th><th>Code</th><th>Title</th><th>Credits</th></tr></thead>
      <tbody>
        <?php while ($c = $courses->fetch_assoc()): ?>
        <tr>
          <td><?=$c['id']?></td>
          <td><?=htmlspecialchars($c['course_code'])?></td>
          <td><?=htmlspecialchars($c['course_title'])?></td>
          <td><?=htmlspecialchars($c['credit_hours'])?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

