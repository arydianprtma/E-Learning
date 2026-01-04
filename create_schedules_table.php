<?php
require_once 'app/Config/config.php';
require_once 'app/Core/Database.php';

try {
    $db = new Database;
    
    $sql = "CREATE TABLE IF NOT EXISTS `schedules` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `class_subject_id` int(11) NOT NULL,
      `day` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
      `start_time` time NOT NULL,
      `end_time` time NOT NULL,
      PRIMARY KEY (`id`),
      KEY `class_subject_id` (`class_subject_id`),
      CONSTRAINT `fk_schedule_cs` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subjects` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->query($sql);
    $db->execute();
    
    echo "Table 'schedules' created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
