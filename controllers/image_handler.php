<?php

session_start();

include("../config/connection.php");

function get_updated_filename($conn, $filename) {
    $image_no = 0;
    $query_check = "SELECT * FROM images_table WHERE image_name = $1";
    $query_check_run = pg_query_params($conn, $query_check, array($filename));

    while(pg_num_rows($query_check_run) > 0) {
        $image_no += 1;
        $temp_filename = $filename . "(" . $image_no . ")";
        $query_check = "SELECT * FROM images_table WHERE image_name = $1";
        $query_check_run = pg_query_params($conn, $query_check, array($temp_filename));
    }

    if($image_no > 0) $filename = $filename . "(" . $image_no . ")";

    return $filename;
}

if (isset($_POST['upload'])) {
    $user_email = $_SESSION['email'];
    $images = $_FILES['gallery_images'];

    $path = "assets/images/user_uploaded/";
    $allowed_extensions = array('gif', 'png', 'jpg', 'jpeg');

    if (!empty($images['name'])) {
        $time_diff = 0;
        foreach ($images['name'] as $key => $image) {
            $filename = $images["name"][$key];
            $tempname = $images["tmp_name"][$key];

            $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
            $file_filename = pathinfo($filename, PATHINFO_FILENAME);

            if (!in_array($file_extension, $allowed_extensions)) {
                $_SESSION['status'] = "You are allowed with only jpg png jpeg and gif";
                header('location: ../');
            } else {
                $file_filename = get_updated_filename($conn, $file_filename);

                $query = "INSERT INTO images_table (path, user_email, image_name, image_ext) VALUES ($1, $2, $3, $4)";
                $query_run = pg_query_params($conn, $query, array($path, $user_email, $file_filename, $file_extension));

                if ($query_run) {
                    $_SESSION['status'] = "Image stored successfully";

                    $folder = $path . $file_filename . "." . $file_extension;
                    move_uploaded_file($tempname, "../" . $folder);

                    header('location: ../index.php');
                } else {
                    $_SESSION['status'] = "Image Not Inserted";
                    header('location: ../index.php');
                }
            }
        }
    }
}

if(isset($_POST["get_images"])) {
    $id = $_POST['image_id'];
    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => "",
        "HTTP_HOST" => $_SERVER['HTTP_HOST'],
        "ids" => []
    ];

    $query = "SELECT * FROM images_table
            WHERE user_email = $1
            AND id < $2
            AND id NOT IN (SELECT image_id FROM deleted_images)
            ORDER BY id DESC LIMIT 12";

    $query_run = pg_query_params($conn, $query, array($user_email, $id));

    if(pg_num_rows($query_run) > 0) {
        $rows = array();
        while ($row = pg_fetch_assoc($query_run)) {
            $encrypted_id = openssl_encrypt($user_email . "-" . $row['id'], $ciphering, $secret_key, $options, $secret_iv);
            $row['encripted_id'] = urlencode($encrypted_id);
            $rows[] = $row;
        }
        $result['images'] = $rows;
    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if(isset($_POST["get_bin_images"])) {
    $id = $_POST['image_id'];
    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => "",
    ];

    $query = "SELECT * FROM images_table
            WHERE user_email = $1
            AND id < $2
            AND id IN (SELECT image_id FROM deleted_images)
            ORDER BY id DESC LIMIT 12";

    $query_run = pg_query_params($conn, $query, array($user_email, $id));

    if(pg_num_rows($query_run) > 0) {
        $rows = array();
        while ($row = pg_fetch_assoc($query_run)) {
            $rows[] = $row;
        }
        $result['images'] = $rows;
    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if (isset($_POST['update_image'])) {
    $id = $_POST['id'];
    $image_name = $_POST['image_name'];
    $user_email = $_SESSION['email'];

    $get_query = "SELECT * FROM images_table WHERE id = $1 AND user_email = $2";
    $get_query_run = pg_query_params($conn, $get_query, array($id, $user_email));

    $old_img = "";
    $new_img = "";

    if(!$image_name) $image_name = "untitled";

    $image_name = get_updated_filename($conn, $image_name);

    if (pg_num_rows($get_query_run) > 0) {
        $row = pg_fetch_assoc($get_query_run);
        $old_img = $row['path'] . $row['image_name'] .  "." . $row['image_ext'];
        $new_img = $row['path'] . $image_name .  "." . $row['image_ext'];
    }

    $query = "UPDATE images_table SET image_name = $1 WHERE id = $2";
    $query_run = pg_query_params($conn, $query, array($image_name, $id));

    $result = [
        "status" => "",
        "image_name" => "",
    ];

    if($query_run) {
        if(rename("../".$old_img, "../".$new_img)) {
            $result["status"] = "success";
            $result["image_name"] = $image_name;
        } else {
            $result["status"] = "image not renamed";
        }
    } else {
        $result["status"] = "query failed";
    }

    echo json_encode($result);
}

if (isset($_POST['delete_image']) || isset($_POST['deleteSelectedImage']) || isset($_POST['deleteAllImages'])) {
    $user_email = $_SESSION['email'];

    $result = [
        "status" => "success",
        "image_ids" => [],
    ];

    $ids = [];

    if(isset($_POST['all']) && $_POST['all']) {
        $images_ids_query = "SELECT id FROM images_table WHERE id NOT IN (SELECT image_id FROM deleted_images)";
        $images_ids_query_run = pg_query($conn, $images_ids_query);

        if(pg_num_rows($images_ids_query_run) > 0) {
            while($row = pg_fetch_assoc($images_ids_query_run)) {
                $ids[] = $row["id"];
            }
        }
    } else {
        $ids = $_POST['id'];
    }

    $idList = implode(',', $ids);

    $get_query = "SELECT * FROM images_table WHERE id IN ($idList) AND user_email = $1";
    $get_query_run = pg_query_params($conn, $get_query, array($user_email));

    if (pg_num_rows($get_query_run) > 0) {
        while ($row = pg_fetch_assoc($get_query_run)) {
            $image_id = $row['id'];
            $query = "INSERT INTO deleted_images (image_id) VALUES ($1)";
            pg_query_params($conn, $query, array($image_id));
        }
    }

    echo json_encode($result);
}

if (isset($_POST['bin_image_delete_form']) || isset($_POST['deleteSelected']) || isset($_POST['deleteAllBin'])) {
    $user_email = $_SESSION['email'];
    $result = [
        "status" => "success",
        "image_ids" => []
    ];

    $ids = [];

    if(isset($_POST['all']) && $_POST['all']) {
        $deleted_ids_query = "SELECT image_id FROM deleted_images";
        $deleted_ids_query_run = pg_query($conn, $deleted_ids_query);

        if(pg_num_rows($deleted_ids_query_run) > 0) {
            while($row = pg_fetch_assoc($deleted_ids_query_run)) {
                $ids[] = $row["image_id"];
            }
        }
    } else {
        $ids = $_POST['id'];
    }

    if (!empty($ids)) {
        $idList = implode(',', $ids);

        // First get the image details before deletion
        $get_query = "SELECT * FROM images_table WHERE id IN ($idList) AND user_email = $1";
        $get_query_run = pg_query_params($conn, $get_query, array($user_email));

        if (pg_num_rows($get_query_run) > 0) {
            while ($row = pg_fetch_assoc($get_query_run)) {
                $old_img = $row['path'] . $row['image_name'] . "." . $row['image_ext'];
                $current_id = $row['id'];

                // Delete from images_table
                $query = "DELETE FROM images_table WHERE id = $1 AND user_email = $2";
                $query_run = pg_query_params($conn, $query, array($current_id, $user_email));

                if ($query_run) {
                    // Delete the physical file
                    if (file_exists("../" . $old_img)) {
                        unlink("../" . $old_img);
                    }

                    // Delete from deleted_images
                    $query_deleted = "DELETE FROM deleted_images WHERE image_id = $1";
                    pg_query_params($conn, $query_deleted, array($current_id));

                    $result["image_ids"][] = $current_id;
                }
            }
        }
    }

    echo json_encode($result);
}

if (isset($_POST['restoreImage']) || isset($_POST['RestoreSelected']) || isset($_POST['RestoreAllBin'])) {
    $result = [
        "status"=> "success",
        "image_ids" => [],
        "all" => ""
    ];

    $ids = [];

    if(isset($_POST['all']) && $_POST['all']) {
        $deleted_ids_query = "SELECT image_id FROM deleted_images";
        $deleted_ids_query_run = pg_query($conn, $deleted_ids_query);

        if(pg_num_rows($deleted_ids_query_run) > 0) {
            while($row = pg_fetch_assoc($deleted_ids_query_run)) {
                $ids[] = $row["image_id"];
            }
        }
    } else {
        $ids = $_POST['id'];
    }

    $result["image_ids"] = $ids;

    if (!empty($ids)) {
        $idList = implode(',', $ids);
        $query = "DELETE FROM deleted_images WHERE image_id IN ($idList)";
        $query_run = pg_query($conn, $query);

        if ($query_run) {
            $result["status"] = "success";
        } else {
            $result["status"] = "failed";
        }
    }

    echo json_encode($result);
}

if( isset( $_POST['next_image'] ) ) {
    $id = $_POST['id'];
    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => $id
    ];

    $query = "SELECT * FROM images_table
              WHERE user_email = $1
              AND id < $2
              AND id NOT IN (SELECT image_id FROM deleted_images)
              ORDER BY id DESC LIMIT 5";

    $query_run = pg_query_params($conn, $query, array($user_email, $id));

    if(pg_num_rows($query_run) > 0) {
        $rows = pg_fetch_all($query_run);
        $result['images'] = $rows;
    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if( isset( $_POST['prev_image'] ) ) {
    $id = $_POST['id'];
    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => $id
    ];

    $query = "SELECT * FROM images_table
              WHERE user_email = $1
              AND id > $2
              AND id NOT IN (SELECT image_id FROM deleted_images)
              ORDER BY id LIMIT 5";

    $query_run = pg_query_params($conn, $query, array($user_email, $id));

    if(pg_num_rows($query_run) > 0) {
        $rows = pg_fetch_all($query_run);
        $result['images'] = $rows;
    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if(isset($_POST['set_session_id_forurl'])) {

    $_SESSION['current_queries']['id'] = $_POST['image_id'];

    echo json_encode($_SESSION['current_queries']);

    // echo $_POST['image_id'];
}

?>
