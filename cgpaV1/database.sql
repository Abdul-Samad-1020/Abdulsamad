-- ============================================
-- UNIVERSITY MANAGEMENT SYSTEM DATABASE SCHEMA
-- Normalized to 3NF
-- ============================================

-- Drop tables if they already exist (safe order)
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS faculty;
DROP TABLE IF EXISTS users;

-- ============================================
-- USER (Supertype)
-- ============================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    role ENUM('STUDENT', 'FACULTY', 'ADMIN') NOT NULL
);

-- ============================================
-- STUDENT (Subtype of USER)
-- ============================================
CREATE TABLE students (
    student_id INT PRIMARY KEY,
    registration_no VARCHAR(50) UNIQUE NOT NULL,
    program VARCHAR(100) NOT NULL,
    current_semester VARCHAR(20),

    CONSTRAINT fk_student_user
        FOREIGN KEY (student_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

-- ============================================
-- FACULTY (Subtype of USER)
-- ============================================
CREATE TABLE faculty (
    faculty_id INT PRIMARY KEY,
    department VARCHAR(100) NOT NULL,
    designation VARCHAR(100) NOT NULL,

    CONSTRAINT fk_faculty_user
        FOREIGN KEY (faculty_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

-- ============================================
-- COURSE
-- ============================================
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(150) NOT NULL,
    credit_hours INT NOT NULL CHECK (credit_hours > 0),
    faculty_id INT,

    CONSTRAINT fk_course_faculty
        FOREIGN KEY (faculty_id)
        REFERENCES faculty(faculty_id)
        ON DELETE SET NULL
);

-- ============================================
-- ENROLLMENT (Student ↔ Course)
-- ============================================
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    year INT NOT NULL,
    status ENUM('ENROLLED', 'COMPLETED', 'DROPPED') NOT NULL,

    CONSTRAINT uq_student_course_sem
        UNIQUE (student_id, course_id, semester, year),

    CONSTRAINT fk_enrollment_student
        FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_enrollment_course
        FOREIGN KEY (course_id)
        REFERENCES courses(course_id)
        ON DELETE CASCADE
);

-- ============================================
-- GRADE (One-to-One with ENROLLMENT)
-- ============================================
CREATE TABLE grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT UNIQUE NOT NULL,
    marks INT CHECK (marks BETWEEN 0 AND 100),
    grade_letter CHAR(2),
    gpa DECIMAL(3,2) CHECK (gpa BETWEEN 0.00 AND 4.00),

    CONSTRAINT fk_grade_enrollment
        FOREIGN KEY (enrollment_id)
        REFERENCES enrollments(enrollment_id)
        ON DELETE CASCADE
);

-- ============================================
-- END OF SCHEMA
-- ============================================
