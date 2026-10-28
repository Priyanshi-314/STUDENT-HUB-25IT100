USE studenthub;

CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(24) NOT NULL,
    email VARCHAR(190) NOT NULL,
    mobile VARCHAR(10) NOT NULL,
    course VARCHAR(100) NOT NULL,
    study_year TINYINT UNSIGNED NOT NULL,
    gender VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_users_username UNIQUE (username),
    CONSTRAINT uq_users_email UNIQUE (email),
    CONSTRAINT chk_users_year CHECK (study_year BETWEEN 1 AND 4),
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS remember_tokens (
    token_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    selector CHAR(32) NOT NULL,
    validator_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_remember_selector UNIQUE (selector),
    CONSTRAINT fk_remember_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_remember_expiry (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS auth_audit_log (
    audit_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    event_type VARCHAR(40) NOT NULL,
    occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auth_audit_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_auth_audit_event_time (event_type, occurred_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Synthetic demo users. Both passwords are password_hash() values, never plain text.
INSERT IGNORE INTO users
(full_name, username, email, mobile, course, study_year, gender, password_hash, role)
VALUES
('Aarav Patel', 'aarav.student', 'aarav.patel@example.edu', '9876500001', 'BTech IT', 2, 'prefer-not', '$2y$10$Ix/fkY4apasF0zazm0gx7u0EWlsu6TbdtFDlQUoyzijhYQFExJ4l2', 'student'),
('StudentHub Administrator', 'hub.admin', 'admin@studenthub.local', '9876500099', 'BTech IT', 4, 'prefer-not', '$2y$10$O1qmQiLpLcvBisspL6fL6O9DLOPNR5B2.P1YaDS8CrvQCIkD68aF6', 'admin');
