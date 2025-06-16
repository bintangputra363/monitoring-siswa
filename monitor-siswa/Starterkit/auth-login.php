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
                                    $_SESSION["kelas_ids"] = $kelas_ids; // boleh kosong
                                    mysqli_stmt_close($stmt_kelas);
                                }
                            }

                            // Redirect sesuai role
                            if ($role == 2) { // Siswa
                                header("location: kegiatan-siswa.php");
                                exit;
                            } elseif ($role == 1) { // Guru
                                header("location: ../Admin/index.php");
                                exit;
                            } elseif ($role == 3) { // Admin
                                header("location: ../Admin/index.php");
                                exit;
                            } elseif ($role == 4) { // Kepala Sekolah
                                header("location: ../Admin/index.php");
                                exit;
                            } else {
                                $useremail_err = "Role tidak dikenali. Hubungi admin!";
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
<style>
    .authentication-bg { background: #232323 !important; }
    .bg-overlay { display: none !important; }
    .card, .card-body {
        background: #303337 !important;
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.5);
    }
    .auth-logo { color: #27c480 !important; font-weight: 700; letter-spacing: 1px; }
    .card-body h4, .card-body .mt-4 { color: #fff !important; font-weight: 500; letter-spacing: 0.5px; }
    .form-control, .input-group-text {
        background: #46484e !important;
        color: #fff !important;
        border-color: #444 !important;
    }
    .form-control::placeholder { color: #bbb !important; }
    .btn-primary, .btn-outline-secondary {
        background: #26b37b !important;
        border-color: #26b37b !important;
        color: #fff !important;
    }
    .btn-primary:hover, .btn-outline-secondary:hover {
        background: #158a5c !important;
        border-color: #158a5c !important;
        color: #fff !important;
    }
    .text-muted { color: #ccc !important; }
    .form-check-label, .form-check-input { color: #fff !important; }
    .form-control:focus { box-shadow: 0 0 0 2px #27c48044; }
</style>
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
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16" id="basic-addon1">
                                        <i class="mdi mdi-account-outline auti-custom-input-icon"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Enter username" name="useremail" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <span class="text-danger"><?php echo $useremail_err; ?></span>
                                <div class="input-group auth-form-group-custom mb-3 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16" id="basic-addon2">
                                        <i class="mdi mdi-lock-outline auti-custom-input-icon"></i>
                                    </span>
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
                                </div>
                                <div class="pt-3 text-center">
                                    <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Log In</button>
                                </div>
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
                                </script> Monitor Siswa | Universitas Pamulang
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
