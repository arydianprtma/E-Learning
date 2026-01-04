<?php
require_once 'app/Config/config.php';
require_once 'app/Core/Database.php';

echo "Running Migration...\n";

try {
    $db = new Database;
    
    // Read SQL file
    $sql = file_get_contents('database_phase2.sql');
    
    // Split by semicolon so we can execute statement by statement if needed, 
    // or just execute the whole block if the driver supports it.
    // PDO can usually execute multiple statements if configured, but let's try raw execution.
    // However, Database class uses prepare/execute.
    // Let's just use raw PDO from the Database class context if possible, but Database class hides PDO.
    // I'll just instantiate a new PDO here for simplicity or extend Database.
    
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec($sql);
    
    echo "Migration Success!\n";
} catch (Exception $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
