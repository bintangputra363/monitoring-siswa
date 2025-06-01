<?php
// Initialize the session
include 'partials/session.php';
// session_start();

// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//     header("location: index.php");
//     exit;
// }

// Include config file
require_once "partials/config.php";

// Define variables and initialize with empty values
$useremail = $password = "";
$useremail_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter user email.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid email format";
    } else {
        $useremail = trim($_POST["useremail"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($useremail_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT users.id, users.username, users.useremail, users.password, roles.id as role
                FROM users 
                JOIN roles ON users.role_user = roles.id 
                WHERE users.useremail = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = $useremail;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if useremail exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $useremail, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["useremail"] = $useremail;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Redirect user based on role
                            if ($role == 2) {
                                header("location: kegiatan-siswa.php");
                            } else {
                                header("location: ../Admin/index.php");
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $useremail_err = "No account found with that user email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Login')); ?>

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
                            <a href="index.php" class="logo-dark">
                                <h1 class="auth-logo text-center">Monitoring</h1>
                            </a>


                            <h4 class="mt-4">Welcome Back !</h4>
                            <p class="text-muted">Sign in to continue.</p>
                        </div>

                        <div class="p-2 mt-5">
                            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($useremail_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16 " id="basic-addon1"><i class="mdi mdi-account-outline auti-custom-input-icon"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter username" name="useremail" aria-label="Username" aria-describedby="basic-addon1">

                                </div>
                                <span class="text-danger"><?php echo $useremail_err; ?></span>
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16" id="basic-addon2"><i class="mdi mdi-lock-outline auti-custom-input-icon"></i></span>
                                    <input type="password" class="form-control" id="userpassword" name="password" placeholder="Enter password" aria-label="Username" aria-describedby="basic-addon1">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <span class="text-danger"><?php echo $password_err; ?></span>
                                <div class="mb-sm-5">
                                    <div class="form-check float-sm-start">
                                        <input type="checkbox" class="form-check-input" id="customControlInline">
                                        <label class="form-check-label" for="customControlInline">Remember me</label>
                                    </div>
                                    <!-- <div class="float-sm-end">
                                        <a href="auth-recoverpw.php" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                    </div> -->
                                </div>

                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Log In</button>
                                </div>

                                <!-- <div class="mt-3 text-center">
                                    <p class="mb-0">Don't have an account ? <a href="auth-register.php" class="fw-medium text-primary"> Register </a> </p>
                                </div> -->

                                <!-- <div class="mt-4 text-center">
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
                                </div> -->
                            </form>
                        </div>

                        <div class="mt-5 text-center">
                            <p>©
                                <script>
                                    function togglePassword() {
                                        const input = document.getElementById("userpassword");
                                        const icon = document.getElementById("toggleIcon");
                                        if (input.type === "password") {
                                            input.type = "text";
                                            icon.classList.remove("fa-eye");
                                            icon.classList.add("fa-eye-slash");
                                        } else {
                                            input.type = "password";
                                            icon.classList.remove("fa-eye-slash");
                                            icon.classList.add("fa-eye");
                                        }
                                    }
                                    document.write(new Date().getFullYear())
                                </script> Monitor Siswa <i class="mdi mdi-heart text-danger"></i> by Elham Hacker
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