<?php
include 'partials/session.php';
include 'partials/main.php';
require_once 'partials/config.php';


$user_id = $_SESSION['id'];

// Ambil siswa_id berdasarkan user_id
$siswa_id = null;
$query = "SELECT id, nama_siswa FROM siswa WHERE user_id = ?";
if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $siswa_id = $row['id'];
        $nama_siswa = $row['nama_siswa'];
    } else {
        die("Siswa tidak ditemukan.");
    }
    mysqli_stmt_close($stmt);
}

// Ambil checkpoint kegiatan tepat waktu hari ini
$tanggal = date('Y-m-d');
$sql = "SELECT COUNT(*) AS tepat_waktu 
        FROM checkpoint_kegiatan 
        WHERE siswa_id = ? 
        AND status = 'Tepat Waktu' 
        AND DATE(waktu_checkpoint) = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "is", $siswa_id, $tanggal);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$tepat_waktu = $row['tepat_waktu'] ?? 0;
mysqli_stmt_close($stmt);

// Ambil total kegiatan siswa
$sql_total = "SELECT COUNT(*) AS total 
              FROM mapping_siswa_kegiatan 
              WHERE siswa_id = ?";
$stmt_total = mysqli_prepare($link, $sql_total);
mysqli_stmt_bind_param($stmt_total, "i", $siswa_id);
mysqli_stmt_execute($stmt_total);
$result_total = mysqli_stmt_get_result($stmt_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_kegiatan = $row_total['total'] ?? 1;
mysqli_stmt_close($stmt_total);

// Hitung persentase progres
$progress = $total_kegiatan > 0 ? ($tepat_waktu / $total_kegiatan) * 100 : 0;
?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Progress Saya')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <h4 class="fw-bold">Progress Kegiatan Hari Ini</h4>
                <p class="text-muted mb-4">Halo, <strong><?= htmlspecialchars($nama_siswa) ?></strong>. Berikut progress kegiatan kamu hari ini:</p>
                <div class="card">
                    <div class="card-body">
                        <h5>Kegiatan Tepat Waktu: <?= $tepat_waktu ?> / <?= $total_kegiatan ?></h5>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress ?>%;">
                                <?= round($progress, 2) ?>%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
    </div>
</div>
<?php include 'partials/vendor-scripts.php'; ?>
<script src="assets/js/app.js"></script>
</body>
</html>
