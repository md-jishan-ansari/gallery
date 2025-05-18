<?php
require_once 'connection.php';

// Function to execute SQL file
function executeSQLFile($conn, $sqlFile) {
    try {
        // Read SQL file
        $sql = file_get_contents($sqlFile);

        // Execute multi query
        if (mysqli_multi_query($conn, $sql)) {
            do {
                // Store first result set
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));

            echo "Database tables created successfully!\n";
        } else {
            echo "Error executing SQL file: " . mysqli_error($conn) . "\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Execute the SQL file
$sqlFile = __DIR__ . '/database.sql';
executeSQLFile($conn, $sqlFile);

// Close connection
mysqli_close($conn);
?>