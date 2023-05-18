<?php include("../components/header.php"); ?>

<!-- navbar includes top navbar as well as login signup models -->
<?php include("../components/navbar.php"); ?>

<?php if (isset($_SESSION['email'])) {

  include("../components/image_upload_form.php");

  include("../components/display_images.php");

} else {

  include("../components/home_default.php");

} ?>

<?php
  // echo $_SERVER['QUERY_STRING'] . "<br>";
  // echo $_SERVER['SCRIPT_NAME'] . "<br>";
  // echo $_SERVER['DOCUMENT_ROOT'] . "<br>";

  // parse_str($_SERVER['QUERY_STRING'], $query_array);
  // print_r($query_array);

?>

<?php include("../components/footer.php"); ?>