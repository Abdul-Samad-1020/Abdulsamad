<?php
require_once 'db_connect.php';
include 'header.php';

// Fetch quick stats
try {
    $stmt_students = $pdo->query("SELECT COUNT(*) FROM students");
    $total_students = $stmt_students->fetchColumn();

    $stmt_faculty = $pdo->query("SELECT COUNT(*) FROM faculty");
    $total_faculty = $stmt_faculty->fetchColumn();

    $stmt_courses = $pdo->query("SELECT COUNT(*) FROM courses");
    $total_courses = $stmt_courses->fetchColumn();

    $stmt_enrollments = $pdo->query("SELECT COUNT(*) FROM enrollments");
    $total_enrollments = $stmt_enrollments->fetchColumn();

} catch (PDOException $e) {
    $error = "Error fetching stats: " . $e->getMessage();
}
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="display-5 text-primary">Dashboard</h1>
        <p class="lead">Welcome to the University Management System.</p>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Students</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_students ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="students.php" class="text-white text-decoration-none stretched-link">Manage Students &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Faculty</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_faculty ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="faculty.php" class="text-white text-decoration-none stretched-link">Manage Faculty &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Courses</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_courses ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="courses.php" class="text-white text-decoration-none stretched-link">Manage Courses &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Enrollments</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_enrollments ?? 0; ?></h2>
                    </div>
                    <i class="fas fa-tasks fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="enrollments.php" class="text-dark text-decoration-none stretched-link">View Enrollments &rarr;</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
