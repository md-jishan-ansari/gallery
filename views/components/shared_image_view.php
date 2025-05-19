<?php
$id = $_GET['img_id'] ?? '';

if (empty($id)) {
    echo '<div class="alert alert-danger">Invalid image link</div>';
    exit;
}

$decripted_data = openssl_decrypt($id, $ciphering, $secret_key, $options, $secret_iv);

if ($decripted_data === false) {
    echo '<div class="alert alert-danger">Invalid or corrupted image link</div>';
    exit;
}

$decripted_data_array = explode("-", $decripted_data);

if (count($decripted_data_array) !== 2) {
    echo '<div class="alert alert-danger">Invalid image data format</div>';
    exit;
}

$user_email = $decripted_data_array[0];
$image_id = $decripted_data_array[1];

if (empty($user_email) || empty($image_id)) {
    echo '<div class="alert alert-danger">Missing image information</div>';
    exit;
}

$query = "SELECT * FROM images_table WHERE id = $1 AND user_email = $2";
$query_run = pg_query_params($conn, $query, array($image_id, $user_email));

if ($query_run === false) {
    echo '<div class="alert alert-danger">Error accessing image</div>';
    exit;
}

if (pg_num_rows($query_run) > 0) {
    while ($row = pg_fetch_assoc($query_run)) {
        $img = $row['path'] . $row['image_name'] . "." . $row['image_ext'];
        $img = "../../" . $img;
        ?>
        <div class="shareImageBox">
            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($row['image_name']); ?>" />
            <p>
                <?php echo htmlspecialchars($row['image_name']); ?>
            </p>
        </div>
        <?php
    }
} else {
    echo '<div class="alert alert-danger">Image not found</div>';
}
?>