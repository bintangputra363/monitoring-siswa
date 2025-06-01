<?php

// Initialize the session
    session_start();

// Include config file
require_once "partials/config.php";

// Define variables and initialize with empty values
$useremail = $username =  $password = $confirm_password = "";
$useremail_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate useremail
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter a useremail.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid email format";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE useremail = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = trim($_POST["useremail"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $useremail_err = "This useremail is already taken.";
                } else {
                    $useremail = trim($_POST["useremail"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

                // Close statement
                mysqli_stmt_close($stmt);
            
        }
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif(strlen(trim($_POST["username"])) >15 ){
        $username_err ="Enter valid username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        $password_err = "Password must contain at least one digit, one lowercase letter, and one uppercase letter.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($useremail_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (useremail, username, password, token) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_useremail, $param_username, $param_password , $param_token);

            // Set parameters
            $param_useremail = $useremail;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_token = bin2hex(random_bytes(50));

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to index page
                header("location: auth-login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<?php include 'partials/main.php'; ?>

<head>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Register'));?>

    <?php include 'partials/head-css.php'; ?>

</head>

<body>
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="bg-overlay"></div>
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <div>
                                <a href="index.php" class="logo-dark">
                                    <img src="assets/images/logo-dark.png" alt="" height="20" class="auth-logo logo-dark mx-auto">
                                </a>
                                <a href="index.php" class="logo-light">
                                    <img src="assets/images/logo-light.png" alt="" height="20" class="auth-logo logo-light mx-auto">
                                </a>
                            </div>

                            <h4 class="font-size-18 mt-4">Register account</h4>
                            <p class="text-muted">Get your free Clivax account now.</p>
                        </div>
                        <div class="p-2 mt-5">
                            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($useremail_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16 " id="basic-addon3"><i class="mdi mdi-email-outline auti-custom-input-icon"></i></span>
                                    <input type="email" class="form-control" id="useremail" placeholder="Enter email" name="useremail" aria-label="email" aria-describedby="basic-addon3">
                                </div>
                                <span class="text-danger"><?php echo $useremail_err; ?></span>
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($username_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16 " id="basic-addon1"><i class="mdi mdi-account-outline auti-custom-input-icon"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter username" aria-label="Username" name="username" aria-describedby="basic-addon1">
                                </div>
                                <span class="text-danger"><?php echo $username_err; ?></span>
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16" id="basic-addon2"><i class="mdi mdi-lock-outline auti-custom-input-icon"></i></span>
                                    <input type="password" class="form-control" id="userpassword" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="basic-addon2">
                                </div>
                                <span class="text-danger"><?php echo $password_err; ?></span>
                                <div class="mb-5">
                                    <div class="form-check float-start">
                                        <input type="checkbox" class="form-check-input" id="customControlInline">
                                        <label class="form-check-label" for="customControlInline">I agree to all Terms and Condition</label>
                                    </div>
                                </div>

                                <div class="text-center pt-3">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Register</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">Already have an account ? <a href="auth-login.php" class="fw-medium text-primary"> Login </a> </p>
                                </div>

                                <div class="mt-4 text-center">
                                    <div class="signin-other-title position-relative">
                                        <h5 class="mb-0 title">or</h5>
                                    </div>
                                    <div class="mt-4 pt-1 hstack gap-3">
                                        <div class="vstack gap-2">
                                            <button type="button" class="btn btn-label-info d-block"><i class="ri-facebook-fill fs-18 align-middle me-2"></i>Sign in with facebook</button>
                                            <button type="button" class="btn btn-label-danger d-block"><i class="ri-google-fill fs-18 align-middle me-2"></i>Sign in with google</button>
                                        </div>
                                        <div class="vstack gap-2">
                                            <button type="button" class="btn btn-label-dark d-block"><i class="ri-github-fill fs-18 align-middle me-2"></i>Sign in with github</button>
                                            <button type="button" class="btn btn-label-success d-block"><i class="ri-twitter-fill fs-18 align-middle me-2"></i>Sign in with twitter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mt-5 text-center">
                            <p>©
                                <script>document.write(new Date().getFullYear())</script> Clivax. Crafted with <i class="mdi mdi-heart text-danger"></i> by Codebucks
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php include 'partials/vendor-scripts.php'; ?>

    <script src="assets/js/app.js"></script>

</body>

</html>