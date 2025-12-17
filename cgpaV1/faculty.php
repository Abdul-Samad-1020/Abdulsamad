<?php
require_once 'db_connect.php';
include 'header.php';

$message = '';

// Handle Add Faculty
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faculty'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];

    try {
        $pdo->beginTransaction();

        // 1. Insert into users
        $stmt = $pdo->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, 'FACULTY')");
        $stmt->execute([$name, $email]);
        $user_id = $pdo->lastInsertId();

        // 2. Insert into faculty
        $stmt = $pdo->prepare("INSERT INTO faculty (faculty_id, department, designation) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $department, $designation]);

        $pdo->commit();
        $message = '<div class="alert alert-success">Faculty added successfully!</div>';
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Deleting from users will CASCADE delete from faculty
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success">Faculty deleted successfully!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Faculty
$stmt = $pdo->query("
    SELECT u.user_id, u.name, u.email, f.department, f.designation 
    FROM faculty f 
    JOIN users u ON f.faculty_id = u.user_id
    ORDER BY u.name ASC
");
$faculty_list = $stmt->fetchAll();
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>Faculty Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
        <i class="fas fa-plus"></i> Add Faculty
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
                        <th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faculty_list as $faculty): ?>
                    <tr>
                        <td><?php echo $faculty['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['email']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['department']); ?></td>
                        <td><?php echo htmlspecialchars($faculty['designation']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $faculty['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Faculty Modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Faculty</h5>
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
                        <label>Department</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Designation</label>
                        <select name="designation" class="form-select" required>
                            <option value="Professor">Professor</option>
                            <option value="Associate Professor">Associate Professor</option>
                            <option value="Assistant Professor">Assistant Professor</option>
                            <option value="Lecturer">Lecturer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_faculty" class="btn btn-primary">Save Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
