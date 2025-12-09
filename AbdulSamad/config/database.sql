
CREATE DATABASE IF NOT EXISTS cgpa_calculator;
USE cgpa_calculator;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    credits DECIMAL(3,1) NOT NULL,
    grade VARCHAR(2) NOT NULL,
    semester INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

