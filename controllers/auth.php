<?php

session_start();

include("../config/connection.php");

if(isset($_POST['get_session_during_reload'])){
    $result = [
        "email" => isset($_SESSION['email']) ? $_SESSION['email'] : null,
        "404" => isset($_SESSION['404']) ? $_SESSION['404'] : null,
        "current_url" => isset($_SESSION["current_url"]) ? $_SESSION["current_url"] : null
    ];

    // Ensure proper JSON encoding
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

function get_past_url($auth_type)  //this is helping function for getting current url
{
    $past_queries_array = $_SESSION["current_queries"];
    $past_script_name = $_SESSION['current_script_name'];

    if($auth_type) {
        $past_queries_array['auth'] = $auth_type;
    } else {
        unset($past_queries_array['auth']);
    }

    $past_queries = http_build_query($past_queries_array);

    $past_url = "";
    if($past_queries) $past_url = $past_script_name . "?" . $past_queries;
    else $past_url = $past_script_name;

    return $past_url;
}

if (isset($_POST['signup'])) {

    $name = $_POST['signup_username'];
    $email = $_POST['signup_email'];
    $password = $_POST['signup_password'];
    $cpassword = $_POST['signup_cpassword'];

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query_email = "SELECT * FROM user_table WHERE email = $1";
    $query_email_run = pg_query_params($conn, $query_email, array($email));

    $result = [
        "status"=> "failed",
        "message" => "",
        "url" => "http://" . $_SERVER['HTTP_HOST'] . get_past_url(""),
    ];

    if ($query_email_run && pg_num_rows($query_email_run)) {
        $result["message"] = "Email is already registered!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result["message"] = "$email is not a valid email address";
    } else if ($password != $cpassword) {
        $result["message"] = "Password and Confirm Password are not same";
    } else {
        $query = "INSERT INTO user_table (username, email, password) VALUES ($1, $2, $3)";
        $query_run = pg_query_params($conn, $query, array($name, $email, $password));

        if ($query_run) {
            $_SESSION['email'] = $email;
            $result["status"] = "success";
        } else {
            $result["message"] = "Some how Registration Failed!";
        }
    }

    echo json_encode($result);
}

if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM user_table WHERE email = $1 AND password = $2";
    $query_run = pg_query_params($conn, $query, array($email, $password));

    $result = [
        "status"=> "",
        "message" => "",
        "url" => "http://" . $_SERVER['HTTP_HOST'] . get_past_url(""),
    ];

    if ($query_run && pg_num_rows($query_run) > 0) {
        $_SESSION['email'] = $email;
        $result["status"] = "success";
        $result["message"] = "login success";
    } else {
        $result["status"] = "failed";
        $result["message"] = "Email or password wrong";
    }

    echo json_encode($result);
}

if (isset($_POST['logout'])) {
    $current_url = get_past_url("");
    session_unset();
    header("location:..{$current_url}");
}

?>