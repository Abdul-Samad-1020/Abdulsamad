
<?php
require_once 'config/database.php';

function addSubject($userId, $subjectName, $credits, $grade, $semester) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO subjects (user_id, subject_name, credits, grade, semester) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $subjectName, $credits, $grade, $semester]);
}

function getSubjects($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE user_id = ? ORDER BY semester, created_at");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteSubject($subjectId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ? AND user_id = ?");
    return $stmt->execute([$subjectId, $userId]);
}
?>

