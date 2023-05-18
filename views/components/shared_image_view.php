<?php
$id = $_GET['img_id'];

$decripted_data = openssl_decrypt ($id, $ciphering, $secret_key, $options, $secret_iv);

$decripted_data_array = explode("-",$decripted_data);
$user_email = $decripted_data_array[0];
$image_id = $decripted_data_array[1];

$query = "SELECT * FROM images_table where id = '$image_id' && user_email = '$user_email'";
$query_run = mysqli_query($conn, $query);

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $row) {
        $img  = $row['path'] . $row['image_name'] . "." . $row['image_ext'];
        $img = "../../" . $img;
        ?>
            <div class="shareImageBox">
                <img src="<?php echo $img; ?>" alt="<?php $row['image_name']; ?>" />
                <p>
                    <?php echo $row['image_name']; ?>
                </p>
            </div>
        <?php
    }
}

?>