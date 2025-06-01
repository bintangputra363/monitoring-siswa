<?php
include 'partials/session.php';
include 'partials/main.php';
require_once 'partials/config.php';



if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

$nama_pengguna = isset($_SESSION['username']) ? strtoupper($_SESSION['username']) : 'PENGGUNA';

date_default_timezone_set('Asia/Jakarta');
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Selamat Siang";
} else {
    $greeting = "Selamat Malam";
}

$siswa_id = null;
$sql_siswa = "SELECT id FROM siswa WHERE user_id = ?";
if ($stmt_siswa = mysqli_prepare($link, $sql_siswa)) {
    mysqli_stmt_bind_param($stmt_siswa, "i", $user_id);
    mysqli_stmt_execute($stmt_siswa);
    $result_siswa = mysqli_stmt_get_result($stmt_siswa);

    if ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
        $siswa_id = $row_siswa['id'];
    } else {
        header("Location: error.php");
        exit;
    }
    mysqli_stmt_close($stmt_siswa);
}
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Daftar Kegiatan Siswa')); ?>
    <?php include 'partials/head-css.php'; ?>
    <style>
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .checkpoint-img {
            width: 400px;
            height: 400px;
            cursor: pointer;
        }
    </style>
</head>

<?php include 'partials/body.php'; ?>

<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>

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
                                <p class="text-muted mb-0">Berikut adalah daftar kegiatan Anda hari ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" center-content">
                    <?php
                    $sql = "SELECT id, nama_kegiatan, jam_mulai, jam_selesai 
                            FROM kegiatan 
                            WHERE id IN (SELECT kegiatan_id FROM mapping_siswa_kegiatan WHERE siswa_id = ?)";

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $siswa_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            $current_time = strtotime(date('H:i:s'));
                            $image_count = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $start_time = strtotime($row['jam_mulai']);
                                $end_time = strtotime($row['jam_selesai']);
                                if ($end_time < $start_time) {
                                    $end_time = strtotime('+1 day', $end_time);
                                }
                                $grace_time = strtotime('+10 minutes', $end_time);

                                $is_in_range = $current_time >= $start_time && $current_time <= $grace_time;

                                if ($is_in_range) {
                                    $image_count++;
                                    $gambar = "assets/images/1.png";
                                    if (stripos($row['nama_kegiatan'], 'Tidur Cepat') !== false) {
                                        $gambar = "assets/images/7.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Bangun Pagi') !== false) {
                                        $gambar = "assets/images/1.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Olahraga') !== false) {
                                        $gambar = "assets/images/3.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Makan sehat') !== false) {
                                        $gambar = "assets/images/5.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Gemar Belajar') !== false) {
                                        $gambar = "assets/images/4.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Bermasyarakat') !== false) {
                                        $gambar = "assets/images/6.png";
                                    } elseif (stripos($row['nama_kegiatan'], 'Beribadah') !== false) {
                                        $gambar = "assets/images/2.png";
                                    }

                                    echo "<div>";
                                    echo "<img src='" . $gambar . "' alt='Gambar Kegiatan' class='checkpoint-img' data-kegiatan-id='" . $row['id'] . "'>";
                                    echo "</div>";
                                }
                            }

                            if ($image_count === 0) {
                                echo "<div class='col-12 text-center'><p>Tidak ada kegiatan yang tersedia saat ini.</p></div>";
                            }
                        } else {
                            echo "<div class='col-12 text-center'><p>Tidak ada kegiatan yang terdaftar.</p></div>";
                        }
                        mysqli_stmt_close($stmt);
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<div class="modal fade" id="checkpointModal" tabindex="-1" aria-labelledby="checkpointModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkpointModalLabel">Informasi Checkpoint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="checkpointModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>
<script src="assets/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkpointImages = document.querySelectorAll('.checkpoint-img');

        checkpointImages.forEach(image => {
            image.addEventListener('click', function () {
                const kegiatanId = this.getAttribute('data-kegiatan-id');

                fetch('partials/checkpoint.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ kegiatan_id: kegiatanId })
                })
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('checkpointModalBody');
                    const modal = new bootstrap.Modal(document.getElementById('checkpointModal'));

                    if (data.success) {
                        modalBody.innerHTML = `<div class="alert alert-success"><strong>Checkpoint:</strong> ${data.timestamp}<br><strong>Status:</strong> ${data.status}</div>`;
                    } else {
                        modalBody.innerHTML = `<div class="alert alert-danger"><strong>Gagal:</strong> ${data.message}</div>`;
                    }

                    modal.show();
                })
                .catch(error => {
                    const modalBody = document.getElementById('checkpointModalBody');
                    const modal = new bootstrap.Modal(document.getElementById('checkpointModal'));

                    modalBody.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat mencatat checkpoint.</div>`;
                    modal.show();
                });
            });
        });
    });
</script>
</body>
</html>
