<?php
    error_reporting(0); //it removes all warning not Error

    $servername = "...";
    $username = "...";
    $password = "...";
    $dbname = "...";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if($conn) {
        // echo "connection ok";
    } else {
        echo "connection failed".mysqli_connect_error();
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