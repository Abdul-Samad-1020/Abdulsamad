sql
-- Database: gpa_calculator
DROP DATABASE IF EXISTS gpa_calculator;
CREATE DATABASE gpa_calculator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gpa_calculator;

-- users: admin & students
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','student') NOT NULL DEFAULT 'student',
  student_id INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- students table
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_number VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(200) NOT NULL,
  program VARCHAR(100),
  semester INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- courses table
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_code VARCHAR(50) NOT NULL UNIQUE,
  course_title VARCHAR(200) NOT NULL,
  credit_hours DECIMAL(4,2) NOT NULL DEFAULT 3.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- grades table: stores per student per course per term
CREATE TABLE grades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  term VARCHAR(50) DEFAULT NULL,
  marks DECIMAL(5,2) DEFAULT NULL,
  grade_letter VARCHAR(5) DEFAULT NULL,
  grade_point DECIMAL(3,2) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- seed admin user (password: admin123)
INSERT INTO users (username, password, role) VALUES ('admin', '');
-- We'll instruct to set password using config-based helper or manually update using PHP password_hash script.

-- OPTIONAL: sample student, courses and grades
INSERT INTO students (student_number, full_name, program, semester) VALUES
('S2025001','Muhammad Saif','BS Software Engineering',3);

INSERT INTO users (username, password, role, student_id) VALUES
('saif','', 'student', 1);

INSERT INTO courses (course_code, course_title, credit_hours) VALUES
('CS101','Introduction to Programming',3.00),
('CS102','Data Structures',3.00),
('CS103','Database Systems',3.00);

-- you may populate grades later via application UI
