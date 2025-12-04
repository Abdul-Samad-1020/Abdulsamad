php
<?php
require_once '../config.php';
require_role('admin');

$grades = $mysqli->query('SELECT g.*, s.full_name, c.course_code, c.course_title FROM grades g JOIN students s ON g.student_id=s.id JOIN courses c ON g.course_id=c.id ORDER BY g.created_at DESC');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Grades</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Grades</h2>
    <a href="add_grade.php">+ Add Grade</a> | <a href="dashboard.php">Back</a>
    <table class="table">
      <thead><tr><th>#</th><th>Student</th><th>Course</th><th>Marks</th><th>Letter</th><th>GP</th><th>Term</th></tr></thead>
      <tbody>
        <?php while ($g = $grades->fetch_assoc()): ?>
        <tr>
          <td><?=$g['id']?></td>
          <td><?=htmlspecialchars($g['full_name'])?></td>
          <td><?=htmlspecialchars($g['course_code'].' - '.$g['course_title'])?></td>
          <td><?=htmlspecialchars($g['marks'])?></td>
          <td><?=htmlspecialchars($g['grade_letter'])?></td>
          <td><?=htmlspecialchars($g['grade_point'])?></td>
          <td><?=htmlspecialchars($g['term'])?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

