<?php $_SESSION['404'] = "'it's 404" ?>

<?php include("../components/header.php"); ?>

<!-- navbar includes top navbar as well as login signup models -->
<?php include("../components/navbar.php"); ?>

<div class="container" style="text-align: center">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>Oops!</h1>
                <h2> 404 Not Found</h2>
                <div class="error-details">
                    Sorry, Requested page not found! <strong>Go back to Home page</strong>
                </div>
                <a href="/"><button class="btn btn-primary navBtn">Home</button></a>
            </div>
        </div>
    </div>
</div>

<?php include("../components/footer.php"); ?>