<?php
require_once 'db_connect.php';
include 'header.php';

$message = '';

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $credit_hours = $_POST['credit_hours'];
    $faculty_id = !empty($_POST['faculty_id']) ? $_POST['faculty_id'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, credit_hours, faculty_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$course_code, $course_name, $credit_hours, $faculty_id]);
        $message = '<div class="alert alert-success">Course added successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Course deleted successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Courses with Faculty Name
$stmt = $pdo->query("
    SELECT c.*, u.name as faculty_name 
    FROM courses c
    LEFT JOIN faculty f ON c.faculty_id = f.faculty_id
    LEFT JOIN users u ON f.faculty_id = u.user_id
    ORDER BY c.course_code ASC
");
$courses = $stmt->fetchAll();

// Fetch Faculty list for dropdown
$faculty_stmt = $pdo->query("
    SELECT f.faculty_id, u.name 
    FROM faculty f 
    JOIN users u ON f.faculty_id = u.user_id 
    ORDER BY u.name ASC
");
$faculty_list = $faculty_stmt->fetchAll();
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>Course Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
        <i class="fas fa-plus"></i> Add Course
    </button>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['credit_hours']); ?></td>
                        <td><?php echo $course['faculty_name'] ? htmlspecialchars($course['faculty_name']) : '<span class="text-muted">Unassigned</span>'; ?></td>
                        <td>
                            <a href="?delete=<?php echo $course['course_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Course Code</label>
                        <input type="text" name="course_code" class="form-control" placeholder="e.g. CS101" required>
                    </div>
                    <div class="mb-3">
                        <label>Course Name</label>
                        <input type="text" name="course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Credit Hours</label>
                        <input type="number" name="credit_hours" class="form-control" min="1" max="6" required>
                    </div>
                    <div class="mb-3">
                        <label>Instructor (Optional)</label>
                        <select name="faculty_id" class="form-select">
                            <option value="">-- Select Faculty --</option>
                            <?php foreach ($faculty_list as $f): ?>
                                <option value="<?php echo $f['faculty_id']; ?>"><?php echo htmlspecialchars($f['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_course" class="btn btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
