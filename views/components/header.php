<?php
session_start();

include("../../config/connection.php");

$_SESSION["current_script_name"] = $_SERVER['SCRIPT_NAME'];
$_SESSION["current_url"] = $_SERVER['REQUEST_URI'];

parse_str($_SERVER['QUERY_STRING'], $query_array);
$_SESSION["current_queries"] = $query_array;

if($_SESSION["current_url"] != "/views/pages/home.php" && !isset($_SESSION["email"]))
    $_SESSION["not_loggedIn"] = "You are not Logged In, Please Login to access this route!";


$Project_title = "Gallery | ";

if($_SESSION['current_script_name'] == "/views/pages/home.php") {

    $Project_title .= "Home";

} else if ( $_SESSION['current_script_name'] == "/views/pages/detailed_image.php" ) {

    $Project_title .= "Detail Images";

} else if( $_SESSION['current_script_name'] == "/views/pages/shared_image.php" ) {

    $Project_title .= "Shared Image";

}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/fabicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/images/fabicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/fabicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/images/fabicon/site.webmanifest">

    <link rel="stylesheet" href="../../assets/css/main.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- for adding a jQuery -->
    <title><?php echo $Project_title ?></title>
</head>

<body>