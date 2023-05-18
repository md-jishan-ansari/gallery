<?php

session_start();

include("../config/connection.php");

function get_updated_filename($conn, $filename) {

    $image_no = 0;
    $query_check = "SELECT * FROM images_table where image_name = '$filename'";
    $query_check_run = mysqli_query($conn, $query_check);

    while(mysqli_num_rows($query_check_run) > 0) {
        $image_no += 1;
        $temp_filename = $filename . "(" . $image_no . ")";
        $query_check = "SELECT * FROM images_table where image_name = '$temp_filename'";
        $query_check_run = mysqli_query($conn, $query_check);
    }

    if($image_no > 0) $filename = $filename . "(" . $image_no . ")";

    return $filename;

}

if (isset($_POST['upload'])) {
    $user_email = $_SESSION['email'];
    $images = $_FILES['gallery_images'];

    $path = "assets/images/user_uploaded/";
    $allowed_extensions = array('gif', 'png', 'jpg', 'jpeg');

    // Get the current time

    if (!empty($images['name'])) {
        $time_diff = 0;
        foreach ($images['name'] as $key => $image) {

            $filename = $images["name"][$key];
            $tempname = $images["tmp_name"][$key];

            // $currentTimestamp = time(); 
            // $current_time = strtotime("+$time_diff second", $currentTimestamp);
            // $time_diff += 1;

            // Image validation 

            // $file_dirname = pathinfo($filename, PATHINFO_DIRNAME);
            // $file_basename = pathinfo($filename, PATHINFO_BASENAME); //with extension
            $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
            $file_filename = pathinfo($filename, PATHINFO_FILENAME); //without extension

            if (!in_array($file_extension, $allowed_extensions)) {
                $_SESSION['status'] = "You are allowed with only jpg png jpeg and gig";
                header('location: ../');
            } else {

                $file_filename = get_updated_filename($conn, $file_filename);

                $query = "INSERT INTO images_table 
                            (path, user_email, image_name, image_ext) 
                            values ('$path','$user_email', '$file_filename', '$file_extension' )";
                
                
                $query_run = mysqli_query($conn, $query);

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
    

    $id= $_POST['image_id'];

    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => "",
        "HTTP_HOST" => $_SERVER['HTTP_HOST'],
        "ids" => []
    ];
    

    $query = "SELECT * FROM images_table 
            where user_email = '$user_email' 
            && id < '$id' 
            && id not in (SELECT image_id from deleted_images) 
            order by id desc limit 12";
    
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $rows = $query_run->fetch_all(MYSQLI_ASSOC);
        
        foreach($rows as $key => $value) {
            // $rows[$key]['encripted_id'][] = $value['id'];

            $encripted_id = openssl_encrypt($user_email . "-" . $rows[$key]['id'], $ciphering, $secret_key, $options, $secret_iv);
            
            $rows[$key]['encripted_id'] = $encripted_id;
            
        }
        $result['images'] = $rows;

    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if(isset($_POST["get_bin_images"])) {
    

    $id= $_POST['image_id'];

    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => "",
    ];
    

    $query = "SELECT * FROM images_table 
            where user_email = '$user_email' 
            && id < '$id' 
            && id in (SELECT image_id from deleted_images) 
            order by id desc limit 12";
    
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {

        $rows = $query_run->fetch_all(MYSQLI_ASSOC);
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

    $get_query = "SELECT * FROM images_table where id = '$id' && user_email = '$user_email'";
    $get_query_run = mysqli_query($conn, $get_query);

    $old_img = "";
    $new_img = "";

    if(!$image_name) $image_name = "untitled";

    $image_name = get_updated_filename($conn, $image_name);
    
    if (mysqli_num_rows($get_query_run) > 0) {
        foreach ($get_query_run as $row) {
            $old_img = $row['path'] . $row['image_name'] .  "." . $row['image_ext'];
            $new_img = $row['path'] . $image_name .  "." . $row['image_ext'];
        }
    }


    $query = "UPDATE images_table set image_name = '$image_name' where id = '$id'";
    $query_run = mysqli_query($conn, $query);

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

if (isset($_POST['delete_image'])) {

    $id = $_POST['id'];
    $user_email = $_SESSION['email'];

    $get_query = "SELECT * FROM images_table where id = '$id' && user_email = '$user_email'";
    $get_query_run = mysqli_query($conn, $get_query);

    $old_img = "";
    $target_image_name = "";
    if (mysqli_num_rows($get_query_run) > 0) {
        foreach ($get_query_run as $row) {
            $old_img = $row['path'] . $row['image_name'] . "." . $row['image_ext'];
            $target_image_name = $row['image_name'];
        }
    }
    $set_delete_query = "INSERT INTO deleted_images (image_id) values ('$id')";

    $set_delete_query_run = mysqli_query($conn, $set_delete_query);


    // $query = "DELETE FROM images_table where id = '$id' && user_email = '$user_email'";
    // $query_run = mysqli_query($conn, $query);

    $result = [
        "status" => "",
        "image_id" => "",
    ];

    // if ($query_run) {
    //     unlink("../" . $old_img);
    //     $result["status"] = "success";
    //     $result["image_id"] = $id;
    // } else {
    //     $result["status"] = "failed";
    //     $result["image_id"] = $id;
    // }

    if ($set_delete_query_run) {
        $result["status"] = "success";
        $result["image_id"] = $id;
    } else {
        $result["status"] = "failed";
        $result["image_id"] = $id;
    }

    echo json_encode($result);
}

if (isset($_POST['bin_image_delete_form']) || isset($_POST['deleteSelected'])  || isset($_POST['deleteAllBin']) ) {


    
    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "image_ids" => []
    ];

    $ids = [];

    if(isset($_POST['all']) && $_POST['all']) {

        $deleted_ids_query = "SELECT image_id FROM deleted_images";
        $deleted_ids_query_run = mysqli_query($conn, $deleted_ids_query); 

        if(mysqli_num_rows($deleted_ids_query_run) > 0) {
            foreach($deleted_ids_query_run as $row) {
                $ids[] = $row["image_id"];
            }
        }

    } else {
        $ids = $_POST['id'];
    }

    $idList = implode(',', $ids);

    $get_query = "SELECT * FROM images_table 
        where id in ($idList) && user_email = '$user_email'";
    
    $get_query_run = mysqli_query($conn, $get_query);

    $rows = $get_query_run->fetch_all(MYSQLI_ASSOC);

    if(mysqli_num_rows($get_query_run) > 0) {
        foreach($get_query_run as $row) {
            $old_img = $row['path'] . $row['image_name'] . "." . $row['image_ext'];
            $target_image_name = $row['image_name'];

            $current_id = $row['id'];

            $query = "DELETE FROM images_table where id = '$current_id' && user_email = '$user_email'";
            $query_run = mysqli_query($conn, $query);

            if ($query_run) {

                unlink("../" . $old_img);
                $result["status"] = "success";
                $result["image_ids"][] = $current_id;

                $query_deleted = "DELETE FROM deleted_images where image_id = '$current_id'";
                $query_deleted_run = mysqli_query($conn, $query_deleted);

            } else {
                $result["status"] = "failed";
            }
        }
    }

    // $old_img = "";
    // $target_image_name = "";

    // if (mysqli_num_rows($get_query_run) > 0) {
    //     foreach ($get_query_run as $row) {
    //         $old_img = $row['path'] . $row['image_name'] . "-" . $row['upload_time'] . "." . $row['image_ext'];
    //         $target_image_name = $row['image_name'];
    //     }
    // }

    // $query = "DELETE FROM images_table where id = '$id' && user_email = '$user_email'";
    // $query_run = mysqli_query($conn, $query);

    // $result = [
    //     "status" => "",
    //     "image_id" => "",
    // ];

    // if ($query_run) {
    //     unlink("../" . $old_img);
    //     $result["status"] = "success";
    //     $result["image_id"] = $id;

    //     $query_deleted = "DELETE FROM deleted_images where image_id = '$id'";
    //     $query_deleted_run = mysqli_query($conn, $query_deleted);

    // } else {
    //     $result["status"] = "failed";
    //     $result["image_id"] = $id;
    // }

    echo json_encode($result);
}


if (isset($_POST['restoreImage'])) {

    $id = $_POST['id'];
    $query = "DELETE FROM deleted_images where image_id = '$id'";
    $query_run = mysqli_query($conn, $query);

    $result = [
        "status" => "",
        "image_id" => "",
    ];

    if ($query_run) {
        $result["status"] = "success";
        $result["image_id"] = $id;

    } else {
        $result["status"] = "failed";
        $result["image_id"] = $id;
    }

    echo json_encode($result);
}





if( isset( $_POST['next_image'] ) ) {
    $id= $_POST['id'];

    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => $id
    ];

    $query = "SELECT * FROM images_table 
                where user_email = '$user_email' 
                && id < '$id'
                && id not in (SELECT image_id from deleted_images) 
                order by id desc limit 5";
    
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $rows = $query_run->fetch_all(MYSQLI_ASSOC);
        $result['images'] = $rows;
    } else {
        $result["status"] = "zeroImages";
        $result["message"] = "There is no any image left!";
    }

    echo json_encode($result);
}

if( isset( $_POST['prev_image'] ) ) {
    $id= $_POST['id'];

    $user_email = $_SESSION['email'];

    $result = [
        "status"=> "success",
        "images" => $id
    ];

    $query = "SELECT * FROM images_table 
                where user_email = '$user_email' 
                && id > '$id' 
                && id not in (SELECT image_id from deleted_images) 
                order by id limit 5";
    
    
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $rows = $query_run->fetch_all(MYSQLI_ASSOC);
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
