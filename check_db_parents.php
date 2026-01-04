<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

echo "\nColumns in parent_profiles:\n";
$db->query("DESCRIBE parent_profiles");
$columns = $db->resultSet();
foreach ($columns as $column) {
    echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
}
