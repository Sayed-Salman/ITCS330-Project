DROP DATABASE IF EXISTS college_course_management;
CREATE DATABASE college_course_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE college_course_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(120) NOT NULL,
    instructor VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample passwords:
-- admin@university.edu = admin123
-- user@student.edu = user123
INSERT INTO users (name, email, password, role) VALUES
('System Administrator', 'admin@university.edu', '$2y$10$ZdLMe2knQ2r9I4El1n4Kuu.dZr9PPSR8sqWL3b6hYruhzLW5/Yx5m', 'admin'),
('Regular Student', 'user@student.edu', '$2y$10$Ds5SQaZtEAKw2ThOeqRBM.qMFBmhwQ3eNX1qc0d7k8C.sW40x4kw6', 'user');

-- Courses table starts empty.
-- Admin users can add courses from add_course.php after logging in.
