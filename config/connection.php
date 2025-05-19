<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $servername = "localhost";
    $port = "5432"; // Default PostgreSQL port
    $username = "...";
    $password = "...";
    $dbname = "gallery";

    // Connect to PostgreSQL with all parameters and options
    $conn_string = "host=$servername port=$port dbname=$dbname user=$username password=$password";
    $conn = pg_connect($conn_string, PGSQL_CONNECT_FORCE_NEW);

    if($conn) {
        // echo "connection ok";
    } else {
        echo "Connection failed: " . pg_last_error() . "\n";
        echo "Connection string: " . $conn_string . "\n";
        echo "PHP version: " . phpversion() . "\n";
        echo "PostgreSQL extension loaded: " . (extension_loaded('pgsql') ? 'Yes' : 'No') . "\n";
    }


// Encription variables

        // Store a string into the variable which
        // need to be Encrypted
        $simple_string = "Welcome to GeeksforGeeks\n";

        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Store the encryption key
        $secret_key = "my_gallery_images";

        // Non-NULL Initialization Vector for encryption
        $secret_iv = '1234567891011121';


        // Use openssl_encrypt() function to encrypt the data

        // $encryption = openssl_encrypt($simple_string, $ciphering, $secret_key, $options, $secret_iv);

        // Use openssl_decrypt() function to decrypt the data

        // $decryption=openssl_decrypt ($encryption, $ciphering, $secret_key, $options, $secret_iv);

?>