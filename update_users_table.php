<?php
require_once 'app/core/Database.php';
require_once 'app/config/config.php';

$db = new Database();

// Add last_login column
try {
    $db->query("ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL DEFAULT NULL");
    $db->execute();
    echo "Added last_login column to users table.\n";
} catch (PDOException $e) {
    echo "last_login column might already exist or error: " . $e->getMessage() . "\n";
}

// Check default value for is_active
try {
    $db->query("ALTER TABLE users MODIFY COLUMN is_active TINYINT(1) DEFAULT 1");
    $db->execute();
    echo "Set default value for is_active to 1.\n";
} catch (PDOException $e) {
    echo "Error modifying is_active: " . $e->getMessage() . "\n";
}
?>