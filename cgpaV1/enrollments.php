<?php
require_once 'db_connect.php';
include 'header.php';

$message = '';

// Handle Add Enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_student'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id, semester, year, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $course_id, $semester, $year, $status]);
        $message = '<div class="alert alert-success">Student enrolled successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Enrollment removed successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Enrollments
$stmt = $pdo->query("
    SELECT e.*, 
           u.name as student_name, 
           s.registration_no,
           c.course_code,
           c.course_name
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN users u ON s.student_id = u.user_id
    JOIN courses c ON e.course_id = c.course_id
    ORDER BY e.year DESC, e.semester DESC
");
$enrollments = $stmt->fetchAll();

// Fetch Students for Dropdown
$students_list = $pdo->query("SELECT s.student_id, u.name, s.registration_no FROM students s JOIN users u ON s.student_id = u.user_id ORDER BY u.name")->fetchAll();

// Fetch Courses for Dropdown
$courses_list = $pdo->query("SELECT course_id, course_code, course_name FROM courses ORDER BY course_code")->fetchAll();
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>Enrollment Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
        <i class="fas fa-plus"></i> New Enrollment
    </button>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrol): ?>
                    <tr>
                        <td><?php echo $enrol['enrollment_id']; ?></td>
                        <td><?php echo htmlspecialchars($enrol['student_name']) . " (" . htmlspecialchars($enrol['registration_no']) . ")"; ?></td>
                        <td><?php echo htmlspecialchars($enrol['course_code']) . " - " . htmlspecialchars($enrol['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($enrol['semester']); ?></td>
                        <td><?php echo htmlspecialchars($enrol['year']); ?></td>
                        <td>
                            <span class="badge <?php echo $enrol['status'] == 'ENROLLED' ? 'bg-info' : ($enrol['status'] == 'COMPLETED' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo htmlspecialchars($enrol['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $enrol['enrollment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($students_list as $s): ?>
                                <option value="<?php echo $s['student_id']; ?>">
                                    <?php echo htmlspecialchars($s['name']) . " (" . htmlspecialchars($s['registration_no']) . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses_list as $c): ?>
                                <option value="<?php echo $c['course_id']; ?>">
                                    <?php echo htmlspecialchars($c['course_code']) . " - " . htmlspecialchars($c['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Fall">Fall</option>
                                <option value="Spring">Spring</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" value="<?php echo date('Y'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="ENROLLED">Enrolled</option>
                            <option value="COMPLETED">Completed</option>
                            <option value="DROPPED">Dropped</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="enroll_student" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
