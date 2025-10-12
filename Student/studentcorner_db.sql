-- Database creation for Student Corner
CREATE DATABASE IF NOT EXISTS mic_database;
USE mic_database;

-- Academic Details Table
CREATE TABLE IF NOT EXISTS academic_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course VARCHAR(50),
    institute_name VARCHAR(100),
    board_university VARCHAR(100),
    year_of_passing INT,
    percentage_cgpa VARCHAR(20),
    certificate VARCHAR(255)
);

-- Family Details Table
CREATE TABLE IF NOT EXISTS family_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    relationship VARCHAR(50),
    occupation VARCHAR(100),
    organization VARCHAR(100),
    mobile_number VARCHAR(20)
);

-- Parents Meeting Table
CREATE TABLE IF NOT EXISTS parents_meeting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meeting_date DATE,
    purpose_of_meeting VARCHAR(255),
    points_discussed TEXT,
    action VARCHAR(255),
    status VARCHAR(50)
);

-- OD Apply Table
CREATE TABLE IF NOT EXISTS od_apply (
    id INT AUTO_INCREMENT PRIMARY KEY,
    od_date DATE NOT NULL,
    od_reason TEXT NOT NULL,
    od_duration INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
