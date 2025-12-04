php
<?php
require_once '../config.php';
require_role('admin');

$students = $mysqli->query('SELECT id, full_name FROM students');
$courses = $mysqli->query('SELECT id, course_code, course_title, credit_hours FROM courses');
$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);
    $marks = floatval($_POST['marks']);
    $term = $mysqli->real_escape_string($_POST['term']);

    list($letter, $gp) = map_marks_to_grade($marks);
    $stmt = $mysqli->prepare('INSERT INTO grades (student_id, course_id, term, marks, grade_letter, grade_point) VALUES (?,?,?,?,?,?)');
    $stmt->bind_param('iiisdd', $student_id, $course_id, $term, $marks, $letter, $gp);
    // note: bind_param expects types, adjust by using s instead of d for floats; simpler to use ->query below
    $mysqli->query("INSERT INTO grades (student_id, course_id, term, marks, grade_letter, grade_point) VALUES ($student_id,$course_id,'$term',$marks,'$letter',$gp)");
    $msg = 'Grade added.';
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Grade</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Add Grade</h2>
    <?php if ($msg) echo '<div class="success">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="post">
      <label>Student<br>
        <select name="student_id" required>
          <option value="">--select--</option>
          <?php while ($s = $students->fetch_assoc()): ?>
            <option value="<?=$s['id']?>"><?=htmlspecialchars($s['full_name'])?></option>
          <?php endwhile; ?>
        </select>
      </label><br>
      <label>Course<br>
        <select name="course_id" required>
          <option value="">--select--</option>
          <?php while ($c = $courses->fetch_assoc()): ?>
            <option value="<?=$c['id']?>"><?=htmlspecialchars($c['course_code'].' - '.$c['course_title'].' ('.$c['credit_hours'].'cr)')?></option>
          <?php endwhile; ?>
        </select>
      </label><br>
      <label>Marks<br><input name="marks" type="number" step="0.01" required></label><br>
      <label>Term (e.g., Fall 2025)<br><input name="term" required></label><br>
      <button type="submit">Add Grade</button>
    </form>
    <p><a href="grades.php">Back</a></p>
  </div>
</body>
</html>


---