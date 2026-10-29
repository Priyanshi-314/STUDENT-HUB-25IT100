USE studenthub;
CREATE TABLE IF NOT EXISTS admin_activity_log (
 audit_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 admin_user_id INT UNSIGNED NOT NULL,
 action VARCHAR(60) NOT NULL,
 details VARCHAR(255) NOT NULL DEFAULT '',
 occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_admin_activity_user FOREIGN KEY (admin_user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
 INDEX idx_admin_activity_time (occurred_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
