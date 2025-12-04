
php
<?php
require_once '../config.php';
require_role('admin');

$students = $mysqli->query('SELECT * FROM students ORDER BY created_at DESC');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Students</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Students</h2>
    <a href="add_student.php">+ Add Student</a> | <a href="dashboard.php">Back</a>
    <table class="table">
      <thead><tr><th>#</th><th>Student No</th><th>Name</th><th>Program</th><th>Semester</th><th>Actions</th></tr></thead>
      <tbody>
        <?php while ($s = $students->fetch_assoc()): ?>
        <tr>
          <td><?=htmlspecialchars($s['id'])?></td>
          <td><?=htmlspecialchars($s['student_number'])?></td>
          <td><?=htmlspecialchars($s['full_name'])?></td>
          <td><?=htmlspecialchars($s['program'])?></td>
          <td><?=htmlspecialchars($s['semester'])?></td>
          <td><a href="edit_student.php?id=<?=$s['id']?>">Edit</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
