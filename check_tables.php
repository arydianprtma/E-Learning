<?php
require_once 'app/Config/config.php';
require_once 'app/Core/Database.php';

try {
    $db = new Database;
    $db->query("SHOW TABLES");
    $tables = $db->resultSet();
    foreach($tables as $table) {
        echo $table['Tables_in_elearning_db'] . "\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
