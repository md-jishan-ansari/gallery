<nav class="navbar shadow-sm navbar-light bg-light">

    <a class="navbar-brand" href="/">Gallery</a>

    <div>
        
        <?php if(!isset($_SESSION['email'])) { ?>
            <button class="btn btn-primary navBtn" data-bs-target="#loginModalToggle" data-bs-toggle="modal">Login</button>
            <button class="btn btn-outline-primary navBtn" data-bs-target="#signupModalToggle" data-bs-toggle="modal">Sign Up</button>
        <?php } else { ?>
            <a href="/views/pages/bin.php"><button class="btn btn-outline-primary navBtn" data-bs-target="#signupModalToggle" data-bs-toggle="modal">Bin</button></a>
            <form action="../../controllers/auth.php" method="POST" style="display: inline-block">
                <button type="submit" class="btn btn-danger navBtn" name="logout" value="logout">logout</button>
            </form>
        <?php } ?>

        
      
    </div>

</nav>

<!-- ************ Login Modal -->

<div class="modal fade" id="loginModalToggle" aria-labelledby="loginModalToggleLabel" tabindex="-1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalToggleLabel">Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- status message added here by using jQuery -->
                <div class="loginWarningContainer">

                    <?php if (isset($_SESSION["not_loggedIn"]) ) { ?>

                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Hey! </strong>
                            <?php echo $_SESSION["not_loggedIn"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        unset($_SESSION['not_loggedIn']);
                    } 
                    ?>

                </div>

                <form id="loginForm">
                    <div class="mb-3">
                        <label for="login_email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="login_email" aria-describedby="emailHelp"
                            name="login_email">
                    </div>
                    <div class="mb-3">
                        <label for="login_assword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login_password" name="login_password">
                    </div>
                    <button type="submit" class="btn btn-primary navBtn">Login</button>
                </form>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary navBtn" data-bs-target="#signupModalToggle" data-bs-toggle="modal">New User Sign
                    Up</button>
            </div>
        </div>
    </div>
</div>

<!-- *************************Signup Modal*********** -->

<div class="modal fade" id="signupModalToggle" aria-labelledby="signupModalToggleLabel" tabindex="-1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="signupModalToggleLabel">Sign Up</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- status message added here by using jQuery -->
                <div class="signupWarningContainer"></div>

                <form id="signupForm">
                    <div class="mb-3">
                        <label for="signup_username" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="signup_username" aria-describedby="emailHelp"
                            name="signup_username">
                    </div>
                    <div class="mb-3">
                        <label for="signup_email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="signup_email" aria-describedby="emailHelp"
                            name="signup_email">
                    </div>
                    <div class="mb-3">
                        <label for="signup_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="signup_password" name="signup_password">
                    </div>
                    <div class="mb-3">
                        <label for="signup_cpassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="signup_cpassword" name="signup_cpassword">
                    </div>
                    <button type="submit" class="btn btn-primary navBtn">Sign Up</button>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary navBtn" data-bs-target="#loginModalToggle" data-bs-toggle="modal">Back to
                    Login</button>
            </div>
        </div>
    </div>
</div>