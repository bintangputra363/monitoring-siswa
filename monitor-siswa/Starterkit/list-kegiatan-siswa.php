<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php
require_once 'partials/config.php';

// Ambil ID user dari sesi login
$user_id = $_SESSION['id'];

// Query untuk mengambil data kegiatan siswa
$sql = "
    SELECT 
        k.nama_kegiatan,
        k.jam_mulai,
        k.jam_selesai
    FROM 
        mapping_siswa_kegiatan msk
    JOIN 
        siswa s ON msk.siswa_id = s.id
    JOIN 
        users u ON s.user_id = u.id
    JOIN 
        kegiatan k ON msk.kegiatan_id = k.id
    WHERE 
        u.id = $user_id
    ORDER BY 
        k.jam_mulai ASC
";

$result = mysqli_query($link, $sql);

if (!$result) {
    die('Query gagal: ' . mysqli_error($link));
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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Kegiatan Siswa</h4>
                            </div>
                            <div class="card-body">
                                
                                <div class="table-responsive-md">
                                    <table class="table text-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($result) > 0) {
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kegiatan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jam_selesai']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>Tidak ada kegiatan tersedia.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<!-- App js -->
<script src="assets/js/app.js"></script>
</body>

</html>