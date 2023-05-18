<div class="home_default">

    <?php if($_SESSION["current_script_name"] == '/views/pages/home.php') { ?>
        <img src="../../assets/images/home_default.png" />
    <?php } else { ?>
            <img src="../../assets/images/default_detail_image.png" />
    <?php } ?>


    <div class="card border-warning shadow-lg home_default_card">

        <div class="card-body">
            <h5 class="card-title">You are not Logged In</h5>
            <p class="card-text">Please Login to access your account.</p>
            <button class="btn btn-primary navBtn" data-bs-target="#loginModalToggle" data-bs-toggle="modal">Login</button>
            <button class="btn btn-outline-primary navBtn" data-bs-target="#signupModalToggle" data-bs-toggle="modal">Sign Up</button>
        </div>
    </div>


</div>