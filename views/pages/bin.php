<?php include("../components/header.php"); ?>

<!-- navbar includes top navbar as well as login signup models -->
<?php include("../components/navbar.php"); ?>

<?php if (isset($_SESSION['email'])) {

  include("../components/bin_images.php");

} else {

  include("../components/home_default.php");

} ?>


<?php include("../components/footer.php"); ?>