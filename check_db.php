<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

echo "Tables:\n";
$db->query("SHOW TABLES");
$tables = $db->resultSet();
foreach ($tables as $table) {
    echo "- " . array_values((array)$table)[0] . "\n";
}

echo "\nColumns in student_profiles:\n";
$db->query("DESCRIBE student_profiles");
$columns = $db->resultSet();
foreach ($columns as $column) {
    echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
}
