<?php
require_once 'app/Config/config.php';
require_once 'app/Core/Database.php';

try {
    $db = new Database;
    
    // Attendances Table
    $sql_attendance = "CREATE TABLE IF NOT EXISTS `attendances` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `class_subject_id` int(11) NOT NULL,
      `student_id` int(11) NOT NULL,
      `date` date NOT NULL,
      `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Hadir',
      `note` varchar(255) DEFAULT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `class_subject_id` (`class_subject_id`),
      KEY `student_id` (`student_id`),
      CONSTRAINT `fk_att_cs` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_att_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->query($sql_attendance);
    $db->execute();
    echo "Table 'attendances' created successfully.\n";

    // Grades Table
    $sql_grades = "CREATE TABLE IF NOT EXISTS `grades` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `class_subject_id` int(11) NOT NULL,
      `student_id` int(11) NOT NULL,
      `type` enum('Tugas','Kuis','UTS','UAS','Praktek') NOT NULL,
      `score` int(11) NOT NULL,
      `description` varchar(100) DEFAULT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `class_subject_id` (`class_subject_id`),
      KEY `student_id` (`student_id`),
      CONSTRAINT `fk_grade_cs` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_grade_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->query($sql_grades);
    $db->execute();
    echo "Table 'grades' created successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
