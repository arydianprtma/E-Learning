-- Database: elearning_db

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator Sekolah'),
(2, 'teacher', 'Guru'),
(3, 'student', 'Siswa'),
(4, 'parent', 'Orang Tua');

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
-- Password for 'admin123' is hashed using PASSWORD_DEFAULT (bcrypt)
-- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi is 'password' (standard laravel test password, useful for dev)
-- Let's generate a real hash for 'admin123': $2y$10$r.7g/7.7.7.7.7.7.7.7.u7.7.7.7.7.7.7.7.7.7.7.7.7.7.7
-- Actually, I will use a simple known hash for 'admin123'
-- Hash: $2y$10$wS2/jFwW6FwW6FwW6FwW6.wW6FwW6FwW6FwW6FwW6FwW6FwW6FwW6 (This is fake)
-- Let's use a standard '123456' hash: $2y$10$jD.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN.XN (Fake)
-- I will use PHP to generate one later or just insert a known one.
-- 'admin123' -> $2y$10$J/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j/j
-- Let's just create the table. I'll create a seed script in PHP to insert the admin user properly so the hash is correct.

COMMIT;
