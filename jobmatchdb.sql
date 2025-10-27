-- Create database
CREATE DATABASE IF NOT EXISTS jobmatchdb
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- Select database
USE jobmatchdb;

-- Create table: members
CREATE TABLE IF NOT EXISTS members (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name      VARCHAR(120)  NOT NULL,
  email          VARCHAR(190)  NOT NULL,
  password_hash  VARCHAR(255)  NOT NULL,     -- store a bcrypt/argon2 hash, not the raw password
  is_premium     TINYINT(1)    NOT NULL DEFAULT 0,
  created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  last_login_at  DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_members_email (email),
  KEY idx_members_created_at (created_at)
) ENGINE=InnoDB ROW_FORMAT=DYNAMIC;
