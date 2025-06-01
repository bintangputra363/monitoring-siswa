<?php 
include 'partials/session.php'; 
include 'partials/main.php';
require_once 'partials/config.php'; 

$user_id = $_SESSION['id'] ?? null; 

if (!$user_id) { 
    header("Location: login.php"); 
    exit; 
} 

// Ambil data siswa berdasarkan user_id 
$sqlSiswa = "SELECT id, nama_siswa FROM siswa WHERE user_id = ?"; 
$stmt = mysqli_prepare($link, $sqlSiswa); 
mysqli_stmt_bind_param($stmt, "i", $user_id); 
mysqli_stmt_execute($stmt); 
$result = mysqli_stmt_get_result($stmt); 
$siswa = mysqli_fetch_assoc($result); 

if (!$siswa) { 
    echo "Siswa tidak ditemukan."; 
    exit; 
} 

$siswa_id = $siswa['id']; 
$nama_siswa = $siswa['nama_siswa']; 
$tanggal = $_GET['tanggal'] ?? date('Y-m-d'); 

// Ambil data kegiatan dan checkpoint siswa pada tanggal tersebut 
$sql = " 
    SELECT 
        k.nama_kegiatan, 
        k.jam_mulai, 
        k.jam_selesai, 
        ck.waktu_checkpoint, 
        ck.status 
    FROM 
        mapping_siswa_kegiatan msk 
    JOIN 
        kegiatan k ON msk.kegiatan_id = k.id 
    LEFT JOIN 
        checkpoint_kegiatan ck ON ck.siswa_id = msk.siswa_id 
        AND ck.kegiatan_id = k.id 
        AND DATE(ck.waktu_checkpoint) = ? 
    WHERE 
        msk.siswa_id = ? 
    ORDER BY 
        k.jam_mulai 
"; 

$stmt = mysqli_prepare($link, $sql); 
mysqli_stmt_bind_param($stmt, "si", $tanggal, $siswa_id); 
mysqli_stmt_execute($stmt); 
$result = mysqli_stmt_get_result($stmt); 
?> 

<!DOCTYPE html> 
<html> 
<head> 
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Laporan Kegiatan - SMP Negeri 4 Kota Tangerang')); ?>
    <!-- <title>Laporan Kegiatan</title>  -->
    <?php include 'partials/head-css.php'; ?> 
    
</head> 
<?php include 'partials/body.php'; ?>
<body> 
    <?php include 'partials/menu.php'; ?> 
    <div class="main-content"> 
        <div class="page-content"> 
            <div class="container-fluid"> 
                <h4>Laporan Kegiatan: <?= htmlspecialchars($nama_siswa) ?></h4> 
                <form method="get" class="mb-3"> 
                    <label for="tanggal">Pilih Tanggal:</label> 
                    <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>"> 
                    <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button> 
                </form> 
                <table class="table table-bordered"> 
                    <thead> 
                        <tr> 
                            <th>Nama Kegiatan</th> 
                            <th>Jam Mulai</th> 
                            <th>Jam Selesai</th> 
                            <th>Waktu Checkpoint</th> 
                            <th>Status</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?> 
                            <tr> 
                                <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td> 
                                <td><?= htmlspecialchars($row['jam_mulai']) ?></td> 
                                <td><?= htmlspecialchars($row['jam_selesai']) ?></td> 
                                <td><?= $row['waktu_checkpoint'] ?? '-' ?></td> 
                                <td><?= $row['status'] ?? '-' ?></td> 
                            </tr> 
                        <?php endwhile; ?> 
                    </tbody> 
                </table> 
            </div> 
        </div> 
    </div> 
    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/vendor-scripts.php'; ?> 
</body> 
</html>
