function calculateGrades() {
    const gradePoints = {
        'A+': 4.0, 'A': 3.7, 'B+': 3.3, 'B': 3.0,
        'C+': 2.7, 'C': 2.3, 'D': 2.0, 'F': 0.0
    };

    const rows = document.querySelectorAll('#subjectsTable tbody tr');
    let totalCredits = 0;
    let totalPoints = 0;

    rows.forEach(row => {
        const credits = parseFloat(row.dataset.credits);
        const grade = row.dataset.grade;
        const points = gradePoints[grade] || 0;

        totalCredits += credits;
        totalPoints += credits * points;
    });

    const cgpa = totalCredits > 0 ? (totalPoints / totalCredits).toFixed(2) : '0.00';
    const percentage = totalCredits > 0 ? ((cgpa / 4.0) * 100).toFixed(2) : '0.00';

    document.getElementById('cgpa').textContent = cgpa;
    document.getElementById('gpa').textContent = cgpa;
    document.getElementById('percentage').textContent = percentage + '%';
}

window.addEventListener('DOMContentLoaded', calculateGrades);
