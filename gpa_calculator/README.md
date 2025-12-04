md
# GPA Calculator Web App

Requirements: PHP 7.4+, MySQL, Apache/Nginx.

1. Place files in your web server folder.
2. Import `db.sql` to create database and tables.
   - e.g. use phpMyAdmin or `mysql -u root -p < db.sql`.
3. Update `config.php` DB credentials if needed.
4. Visit `http://localhost/gpa_calculator/index.php`.
5. Default seeded admin username `admin` with password `admin123`.
6. Add courses, students, enter grades and view results.

Notes:
- Passwords are hashed using PHP `password_hash`.
- Grade mapping is in `config.php` — adjust scale to match your institution.
- This is a simple instructional app; for production, add CSRF protections and sanitize inputs thoroughly.