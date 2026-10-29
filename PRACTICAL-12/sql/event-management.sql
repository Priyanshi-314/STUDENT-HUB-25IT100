USE studenthub;

-- Run after PRACTICAL-08/sql/studenthub.sql. Import this migration once.
ALTER TABLE events
    ADD COLUMN status ENUM('open', 'closed') NOT NULL DEFAULT 'open' AFTER description,
    ADD COLUMN poster_path VARCHAR(255) NULL AFTER status;

CREATE TABLE event_management_audit (
    audit_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NULL,
    event_type ENUM('created', 'updated', 'deleted') NOT NULL,
    occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_event_management_admin FOREIGN KEY (admin_user_id)
        REFERENCES users(user_id) ON DELETE RESTRICT,
    CONSTRAINT fk_event_management_event FOREIGN KEY (event_id)
        REFERENCES events(event_id) ON DELETE SET NULL,
    INDEX idx_event_management_time (occurred_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
