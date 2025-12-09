<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'config/database.php';
require_once 'includes/functions.php';

// Handle add subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
    addSubject($_SESSION['user_id'], $_POST['subject_name'], $_POST['credits'], $_POST['grade'], $_POST['semester']);
}

// Handle delete subject
if (isset($_GET['delete'])) {
    deleteSubject($_GET['delete'], $_SESSION['user_id']);
    header('Location: dashboard.php');
    exit();
}

$subjects = getSubjects($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CGPA Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 mb-6 border border-white/20">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">CGPA Calculator</h1>
                    <p class="text-white/80">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                </div>
                <a href="logout.php" class="bg-red-500/80 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition">
                    Logout
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div id="stats" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 border border-white/20">
                <p class="text-white/80 text-sm">CGPA</p>
                <p class="text-4xl font-bold text-white" id="cgpa">0.00</p>
            </div>
            <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 border border-white/20">
                <p class="text-white/80 text-sm">Current GPA</p>
                <p class="text-4xl font-bold text-white" id="gpa">0.00</p>
            </div>
            <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 border border-white/20">
                <p class="text-white/80 text-sm">Percentage</p>
                <p class="text-4xl font-bold text-white" id="percentage">0.00%</p>
            </div>
        </div>

        <!-- Add Subject Form -->
        <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 mb-6 border border-white/20">
            <h2 class="text-2xl font-bold text-white mb-4">Add Subject</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="text" name="subject_name" placeholder="Subject Name" required
                    class="px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                <input type="number" name="credits" placeholder="Credits" step="0.5" min="0.5" max="10" required
                    class="px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                <select name="grade" required
                    class="px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                    <option value="">Select Grade</option>
                    <option value="A+">A+ (4.0)</option>
                    <option value="A">A (3.7)</option>
                    <option value="B+">B+ (3.3)</option>
                    <option value="B">B (3.0)</option>
                    <option value="C+">C+ (2.7)</option>
                    <option value="C">C (2.3)</option>
                    <option value="D">D (2.0)</option>
                    <option value="F">F (0.0)</option>
                </select>
                <input type="number" name="semester" placeholder="Semester" min="1" max="8" required
                    class="px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                <button type="submit" name="add_subject"
                    class="bg-white text-purple-600 font-semibold py-2 rounded-lg hover:bg-white/90 transition">
                    Add Subject
                </button>
            </form>
        </div>

        <!-- Subjects Table -->
        <div class="backdrop-blur-lg bg-white/10 rounded-2xl shadow-2xl p-6 border border-white/20">
            <h2 class="text-2xl font-bold text-white mb-4">Your Subjects</h2>
            <div class="overflow-x-auto">
                <table class="w-full" id="subjectsTable">
                    <thead>
                        <tr class="text-white border-b border-white/20">
                            <th class="text-left py-3 px-4">Subject</th>
                            <th class="text-left py-3 px-4">Credits</th>
                            <th class="text-left py-3 px-4">Grade</th>
                            <th class="text-left py-3 px-4">Semester</th>
                            <th class="text-left py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-white/90">
                        <?php foreach($subjects as $subject): ?>
                        <tr class="border-b border-white/10" data-credits="<?php echo $subject['credits']; ?>" data-grade="<?php echo $subject['grade']; ?>">
                            <td class="py-3 px-4"><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td class="py-3 px-4"><?php echo $subject['credits']; ?></td>
                            <td class="py-3 px-4"><?php echo $subject['grade']; ?></td>
                            <td class="py-3 px-4">Sem <?php echo $subject['semester']; ?></td>
                            <td class="py-3 px-4">
                                <a href="?delete=<?php echo $subject['id']; ?>" 
                                   class="text-red-400 hover:text-red-300"
                                   onclick="return confirm('Delete this subject?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>


