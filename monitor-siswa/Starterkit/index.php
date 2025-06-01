<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php
// if ($_SESSION["role"] != 2) {
//     header("Location: auth-login.php");
//     exit;
// }

// Ambil nama pengguna dari session
$nama_pengguna = isset($_SESSION['username']) ? strtoupper($_SESSION['username']) : 'PENGGUNA';

//waktu sekarang berdasarkan zona Indonesia
date_default_timezone_set('Asia/Jakarta');
$hour = date('H'); // 00 - 23

if ($hour >= 5 && $hour < 12) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Selamat Siang";
} else {
    $greeting = "Selamat Malam";
}
?>
<head>

    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard'));?>

    <?php include 'partials/head-css.php'; ?>

</head>

<?php include 'partials/body.php'; ?>

<div id="layout-wrapper">

    <?php include 'partials/menu.php'; ?>

    <!-- Start right Content here -->

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="fs-16 fw-semibold mb-1 mb-md-2">
                                    <?php echo $greeting; ?>, <span class="text-primary"><?php echo $nama_pengguna; ?>!</span>
                                </h4>
                                <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                            </div>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Clivax</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!--    end row -->
            </div>
            <!-- end container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'partials/footer.php'; ?>

    </div>
    <!-- end main content-->
</div>
<!-- end layout-wrapper -->

<?php include 'partials/vendor-scripts.php'; ?>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="assets/js/pages/dashboard.init.js"></script>
<script src="assets/js/app.js"></script>
</body>

</html>
