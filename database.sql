CREATE DATABASE college_feedback;

USE college_feedback;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','faculty','admin') DEFAULT 'student',
    department VARCHAR(100),
    semester VARCHAR(50)
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(150) NOT NULL,
    department VARCHAR(100),
    faculty_id INT
);

CREATE TABLE feedback_forms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    active INT DEFAULT 1
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_id INT NOT NULL,
    question VARCHAR(300) NOT NULL,
    category VARCHAR(50) NOT NULL
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_id INT,
    student_id INT,
    course_id INT,
    faculty_id INT,
    anonymous INT DEFAULT 0,
    comments TEXT,
    sentiment VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feedback_id INT,
    question_id INT,
    rating INT
);

CREATE TABLE feedback_cycles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    start_date DATE,
    end_date DATE,
    status ENUM('Scheduled','Active','Closed') DEFAULT 'Scheduled'
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    is_read INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users
(name,email,password,role,department,semester)
VALUES
('Admin','admin@college.com',SHA2('password',256),'admin','Administration',''),
('Faculty One','faculty@college.com',SHA2('password',256),'faculty','Computer Science',''),
('Student One','student@college.com',SHA2('password',256),'student','Computer Science','Semester 5');

INSERT INTO courses
(course_name,department,faculty_id)
VALUES
('Web Technology','Computer Science',2);

INSERT INTO feedback_forms
(title,description)
VALUES
('Course and Faculty Evaluation','Evaluate course and faculty performance.');

INSERT INTO questions
(form_id,question,category)
VALUES
(1,'Course content is clear and useful.','Course'),
(1,'Teacher explains concepts clearly.','Teaching'),
(1,'Teacher communicates effectively.','Communication'),
(1,'Teacher is punctual and regular.','Punctuality'),
(1,'Overall satisfaction with this course.','General');

INSERT INTO feedback_cycles
(title,start_date,end_date,status)
VALUES
('Semester 5 Feedback','2026-08-01','2026-12-31','Active');

INSERT INTO notifications
(user_id,message)
VALUES
(3,'Please submit your feedback before the feedback cycle closes.');