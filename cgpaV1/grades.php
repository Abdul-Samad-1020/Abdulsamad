<?php
require_once 'db_connect.php';
include 'header.php';

$message = '';

function calculateGrade($marks) {
    if ($marks >= 85) return ['A', 4.00];
    if ($marks >= 80) return ['A-', 3.67];
    if ($marks >= 75) return ['B+', 3.33];
    if ($marks >= 71) return ['B', 3.00];
    if ($marks >= 68) return ['B-', 2.67];
    if ($marks >= 64) return ['C+', 2.33];
    if ($marks >= 61) return ['C', 2.00];
    if ($marks >= 58) return ['C-', 1.67];
    if ($marks >= 54) return ['D+', 1.33];
    if ($marks >= 50) return ['D', 1.00];
    return ['F', 0.00];
}

// Handle Add/Update Grade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_grade'])) {
    $enrollment_id = $_POST['enrollment_id'];
    $marks = (int)$_POST['marks'];

    list($grade_letter, $gpa) = calculateGrade($marks);

    try {
        // Check if grade already exists for this enrollment
        $check = $pdo->prepare("SELECT grade_id FROM grades WHERE enrollment_id = ?");
        $check->execute([$enrollment_id]);
        $existing = $check->fetch();

        if ($existing) {
            // Update
            $stmt = $pdo->prepare("UPDATE grades SET marks=?, grade_letter=?, gpa=? WHERE enrollment_id=?");
            $stmt->execute([$marks, $grade_letter, $gpa, $enrollment_id]);
            $message = '<div class="alert alert-success">Grade updated successfully!</div>';
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO grades (enrollment_id, marks, grade_letter, gpa) VALUES (?, ?, ?, ?)");
            $stmt->execute([$enrollment_id, $marks, $grade_letter, $gpa]);
            $message = '<div class="alert alert-success">Grade assigned successfully!</div>';
        }
        
        // Also update enrollment status to COMPLETED if not already
        $pdo->prepare("UPDATE enrollments SET status='COMPLETED' WHERE enrollment_id=?")->execute([$enrollment_id]);

    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Enrollments with Grades
$stmt = $pdo->query("
    SELECT e.enrollment_id, e.semester, e.year, e.status,
           u.name as student_name, 
           c.course_code,
           c.course_name,
           g.marks, g.grade_letter, g.gpa
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN users u ON s.student_id = u.user_id
    JOIN courses c ON e.course_id = c.course_id
    LEFT JOIN grades g ON e.enrollment_id = g.enrollment_id
    ORDER BY e.year DESC, e.semester DESC, u.name ASC
");
$records = $stmt->fetchAll();
?>

<div class="page-header">
    <h2>Grades Management</h2>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Sem/Year</th>
                        <th>Status</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>GPA</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['course_code']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($row['course_name']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['semester']) . ' ' . $row['year']; ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        
                        <?php if ($row['marks'] !== null): ?>
                            <td class="fw-bold"><?php echo $row['marks']; ?></td>
                            <td><span class="badge bg-primary"><?php echo $row['grade_letter']; ?></span></td>
                            <td><?php echo $row['gpa']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="openGradeModal(<?php echo $row['enrollment_id']; ?>, <?php echo $row['marks']; ?>, '<?php echo addslashes($row['student_name']); ?>', '<?php echo addslashes($row['course_code']); ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        <?php else: ?>
                            <td colspan="3" class="text-center text-muted"><em>Not Graded</em></td>
                            <td>
                                <button class="btn btn-sm btn-success" 
                                        onclick="openGradeModal(<?php echo $row['enrollment_id']; ?>, '', '<?php echo addslashes($row['student_name']); ?>', '<?php echo addslashes($row['course_code']); ?>')">
                                    <i class="fas fa-plus-circle"></i> Grade
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Grade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="enrollment_id" id="modal_enrollment_id">
                    <p><strong>Student:</strong> <span id="modal_student_name"></span></p>
                    <p><strong>Course:</strong> <span id="modal_course_code"></span></p>
                    
                    <div class="mb-3">
                        <label>Marks (0-100)</label>
                        <input type="number" name="marks" id="modal_marks" class="form-control" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_grade" class="btn btn-primary">Save Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openGradeModal(enrollmentId, currentMarks, studentName, courseCode) {
    document.getElementById('modal_enrollment_id').value = enrollmentId;
    document.getElementById('modal_marks').value = currentMarks;
    document.getElementById('modal_student_name').innerText = studentName;
    document.getElementById('modal_course_code').innerText = courseCode;
    
    var myModal = new bootstrap.Modal(document.getElementById('gradeModal'));
    myModal.show();
}
</script>

<?php include 'footer.php'; ?>
