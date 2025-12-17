<?php
require_once 'db_connect.php';
include 'header.php';

$message = '';

// Handle Add Student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $reg_no = $_POST['registration_no'];
    $program = $_POST['program'];
    $semester = $_POST['semester'];

    try {
        $pdo->beginTransaction();

        // 1. Insert into users
        $stmt = $pdo->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, 'STUDENT')");
        $stmt->execute([$name, $email]);
        $user_id = $pdo->lastInsertId();

        // 2. Insert into students
        $stmt = $pdo->prepare("INSERT INTO students (student_id, registration_no, program, current_semester) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $reg_no, $program, $semester]);

        $pdo->commit();
        $message = '<div class="alert alert-success">Student added successfully!</div>';
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Deleting from users will CASCADE delete from students
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Student deleted successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Students
$stmt = $pdo->query("
    SELECT u.user_id, u.name, u.email, s.registration_no, s.program, s.current_semester 
    FROM students s 
    JOIN users u ON s.student_id = u.user_id
    ORDER BY u.name ASC
");
$students = $stmt->fetchAll();
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>Student Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-plus"></i> Add Student
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
                        <th>Name</th>
                        <th>Reg No</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['registration_no']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['program']); ?></td>
                        <td><?php echo htmlspecialchars($student['current_semester']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $student['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Registration No</label>
                        <input type="text" name="registration_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Program</label>
                        <input type="text" name="program" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Current Semester</label>
                         <select name="semester" class="form-select" required>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                            <option value="4th">4th</option>
                            <option value="5th">5th</option>
                            <option value="6th">6th</option>
                            <option value="7th">7th</option>
                            <option value="8th">8th</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_student" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
