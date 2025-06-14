<?php
include 'partials/session.php';
require_once "partials/config.php";

$useremail = $password = "";
$useremail_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter user email.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid email format";
    } else {
        $useremail = trim($_POST["useremail"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($useremail_err) && empty($password_err)) {
        $sql = "SELECT users.id, users.username, users.useremail, users.password, roles.id as role
                FROM users 
                JOIN roles ON users.role_user = roles.id 
                WHERE users.useremail = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $useremail);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $useremail, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["useremail"] = $useremail;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Jika GURU (role = 1), ambil kelas yang dia pegang
                            if ($role == 1) {
                                $sql_kelas = "SELECT kelas_id FROM mapping_guru_kelas WHERE guru_id = ?";
                                if ($stmt_kelas = mysqli_prepare($link, $sql_kelas)) {
                                    mysqli_stmt_bind_param($stmt_kelas, "i", $id);
                                    mysqli_stmt_execute($stmt_kelas);
                                    $result_kelas = mysqli_stmt_get_result($stmt_kelas);

                                    $kelas_ids = [];
                                    while ($row = mysqli_fetch_assoc($result_kelas)) {
                                        $kelas_ids[] = $row['kelas_id'];
                                    }

                                    $_SESSION["kelas_ids"] = $kelas_ids; // boleh kosong, artinya akses semua
                                    mysqli_stmt_close($stmt_kelas);
                                }
                            }

                            // Redirect sesuai role
                            if ($role == 2) {
                                header("location: kegiatan-siswa.php");
                                exit;
                            } else {
                                header("location: ../Admin/index.php");
                                exit;
                            }
                        } else {
                            $password_err = "Password salah.";
                        }
                    }
                } else {
                    $useremail_err = "Akun tidak ditemukan.";
                }
            } else {
                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
            }

            mysqli_stmt_close($stmt);
        }
    }

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