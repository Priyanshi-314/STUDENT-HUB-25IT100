CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;

CREATE TABLE IF NOT EXISTS students (
 student_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 mobile VARCHAR(10) NOT NULL,
 program VARCHAR(100) NOT NULL,
 study_year TINYINT UNSIGNED NOT NULL,
 gender VARCHAR(20) NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT chk_student_year CHECK (study_year BETWEEN 1 AND 4)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
 event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 category VARCHAR(60) NOT NULL,
 event_date DATE NULL,
 venue VARCHAR(150) NOT NULL,
 description TEXT NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
 registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 student_id INT UNSIGNED NOT NULL,
 event_id INT UNSIGNED NOT NULL,
 status ENUM('registered','cancelled','attended') NOT NULL DEFAULT 'registered',
 registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT uq_student_event UNIQUE (student_id,event_id),
 CONSTRAINT fk_registration_student FOREIGN KEY (student_id) REFERENCES students(student_id) ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_registration_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON UPDATE CASCADE ON DELETE RESTRICT,
 INDEX idx_registration_event (event_id), INDEX idx_registration_status (status)
) ENGINE=InnoDB;

-- Synthetic seed records for the Practical 8 connection demonstration.
INSERT IGNORE INTO students (student_id,full_name,email,mobile,program,study_year,gender) VALUES
(1,'Aarav Patel','aarav.patel@example.edu','9876500001','B.Tech Information Technology',2,'prefer-not'),
(2,'Aditi Shah','aditi.shah@example.edu','9876500002','B.Tech Information Technology',2,'prefer-not'),
(3,'Dev Mehta','dev.mehta@example.edu','9876500003','B.Tech Computer Engineering',3,'prefer-not'),
(4,'Diya Desai','diya.desai@example.edu','9876500004','B.Sc. Information Technology',1,'prefer-not');

INSERT IGNORE INTO events (event_id,title,category,event_date,venue,description) VALUES
(1,'Web Design Studio','Workshop','2027-02-12','Innovation Lab','Sample event record for schema demonstration.'),
(2,'Campus Code Sprint','Competition','2027-02-18','Computing Lab 2','Sample event record for schema demonstration.'),
(3,'Student Club Open House','Community','2027-02-24','Central Courtyard','Sample event record for schema demonstration.');

INSERT IGNORE INTO registrations (student_id,event_id,status) VALUES
(1,1,'registered'),(1,2,'registered'),(2,1,'attended'),(2,3,'registered'),(3,2,'registered'),(4,3,'registered');
