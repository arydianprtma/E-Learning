<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

echo "\nRoles:\n";
$db->query("SELECT * FROM roles");
$roles = $db->resultSet();
foreach ($roles as $role) {
    echo "- " . $role['id'] . ": " . $role['name'] . "\n";
}

echo "\nCheck if any parent exists:\n";
$db->query("SELECT * FROM parent_profiles LIMIT 1");
$parent = $db->single();
print_r($parent);

