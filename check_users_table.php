<?php
require_once 'app/core/Database.php';
require_once 'app/config/config.php';

$db = new Database();
$db->query("DESCRIBE users");
$columns = $db->resultSet();

echo "Users Table Structure:\n";
foreach ($columns as $col) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}
?>