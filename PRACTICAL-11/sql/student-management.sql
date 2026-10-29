USE studenthub;

-- Run after PRACTICAL-08/sql/studenthub.sql. Adds the optional soft-delete state.
ALTER TABLE students
    ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active' AFTER gender;

-- Twenty synthetic rows for the practical dataset (not real student records).
INSERT INTO students (full_name, email, mobile, program, study_year, gender) VALUES
('Mira Joshi', 'mira.joshi@example.edu', '9876500101', 'B.Tech Information Technology', 2, 'prefer-not'),
('Kabir Shah', 'kabir.shah@example.edu', '9876500102', 'B.Tech Computer Engineering', 2, 'prefer-not'),
('Anaya Patel', 'anaya.patel@example.edu', '9876500103', 'B.Tech Information Technology', 1, 'prefer-not'),
('Rohan Desai', 'rohan.desai@example.edu', '9876500104', 'B.Tech Information Technology', 3, 'prefer-not'),
('Isha Mehta', 'isha.mehta@example.edu', '9876500105', 'B.Sc. Information Technology', 2, 'prefer-not'),
('Arjun Shah', 'arjun.shah@example.edu', '9876500106', 'B.Tech Computer Engineering', 1, 'prefer-not'),
('Sana Trivedi', 'sana.trivedi@example.edu', '9876500107', 'B.Tech Information Technology', 4, 'prefer-not'),
('Vivaan Patel', 'vivaan.patel@example.edu', '9876500108', 'BCA', 2, 'prefer-not'),
('Aarohi Desai', 'aarohi.desai@example.edu', '9876500109', 'B.Tech Information Technology', 3, 'prefer-not'),
('Neil Mehta', 'neil.mehta@example.edu', '9876500110', 'B.Tech Computer Engineering', 4, 'prefer-not'),
('Kiara Joshi', 'kiara.joshi@example.edu', '9876500111', 'B.Sc. Information Technology', 1, 'prefer-not'),
('Dhruv Shah', 'dhruv.shah@example.edu', '9876500112', 'B.Tech Information Technology', 2, 'prefer-not'),
('Myra Patel', 'myra.patel@example.edu', '9876500113', 'BCA', 3, 'prefer-not'),
('Reyansh Desai', 'reyansh.desai@example.edu', '9876500114', 'B.Tech Computer Engineering', 2, 'prefer-not'),
('Aditi Mehta', 'aditi.mehta@example.edu', '9876500115', 'B.Tech Information Technology', 1, 'prefer-not'),
('Ishaan Joshi', 'ishaan.joshi@example.edu', '9876500116', 'B.Sc. Information Technology', 4, 'prefer-not'),
('Pari Shah', 'pari.shah@example.edu', '9876500117', 'B.Tech Information Technology', 3, 'prefer-not'),
('Atharv Patel', 'atharv.patel@example.edu', '9876500118', 'BCA', 1, 'prefer-not'),
('Navya Desai', 'navya.desai@example.edu', '9876500119', 'B.Tech Computer Engineering', 3, 'prefer-not'),
('Yuvaan Mehta', 'yuvaan.mehta@example.edu', '9876500120', 'B.Tech Information Technology', 4, 'prefer-not');
