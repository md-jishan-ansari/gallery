<?php
require_once 'connection.php';

// Function to execute SQL file
function executeSQLFile($conn, $sqlFile) {
    try {
        // Read SQL file
        $sql = file_get_contents($sqlFile);

        // Execute the SQL
        $result = pg_query($conn, $sql);

        if ($result) {
            echo "Database tables created successfully!\n";
        } else {
            echo "Error executing SQL file: " . pg_last_error($conn) . "\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Execute the SQL file
$sqlFile = __DIR__ . '/database.sql';
executeSQLFile($conn, $sqlFile);

// Close connection
pg_close($conn);
?>