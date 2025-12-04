
php
<?php
require_once '../config.php';
require_role('student');
$student_id = $_SESSION['user']['student_id'];
// fetch student
$student = $mysqli->query("SELECT * FROM students WHERE id=$student_id")->fetch_assoc();
// fetch grades grouped by term
$grades_res = $mysqli->query("SELECT g.*, c.course_code, c.course_title, c.credit_hours FROM grades g JOIN courses c ON g.course_id=c.id WHERE g.student_id=$student_id ORDER BY g.term");

// group by term
$terms = [];
while ($r = $grades_res->fetch_assoc()) {
    $terms[$r['term']][] = $r;
}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Welcome, <?=htmlspecialchars($student['full_name'])?></h2>
    <p>Student #: <?=htmlspecialchars($student['student_number'])?> | Program: <?=htmlspecialchars($student['program'])?></p>
    <nav><a href="../logout.php">Logout</a></nav>

    <?php if (empty($terms)): ?>
      <p>No grades available yet.</p>
    <?php else: ?>
      <?php foreach ($terms as $term => $rows):
          $items = [];
          foreach ($rows as $r) $items[] = ['credit_hours'=>$r['credit_hours'],'grade_point'=>$r['grade_point']];
          $gpa = calculate_gpa($items);
      ?>
        <section class="term">
          <h3><?=htmlspecialchars($term)?> — GPA: <?=$gpa?></h3>
          <table class="table">
            <thead><tr><th>Course</th><th>Credits</th><th>Marks</th><th>Letter</th><th>GP</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?=htmlspecialchars($r['course_code'].' - '.$r['course_title'])?></td>
                <td><?=$r['credit_hours']?></td>
                <td><?=$r['marks']?></td>
                <td><?=$r['grade_letter']?></td>
                <td><?=$r['grade_point']?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </section>
      <?php endforeach; ?>

      <!-- CGPA calculation across all terms -->
      <?php
        // get all grades
        $allgrades = $mysqli->query("SELECT g.*, c.credit_hours FROM grades g JOIN courses c ON g.course_id=c.id WHERE g.student_id=$student_id");
        $items_all = [];
        while ($ar = $allgrades->fetch_assoc()) {
            $items_all[] = ['credit_hours'=>$ar['credit_hours'],'grade_point'=>$ar['grade_point']];
        }
        $cgpa = calculate_gpa($items_all);
      ?>
      <div class="cgpa">Cumulative CGPA: <strong><?=$cgpa?></strong></div>
    <?php endif; ?>
  </div>
</body>
</html>
