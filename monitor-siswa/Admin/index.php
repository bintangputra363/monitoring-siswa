<?php
include 'partials/session.php';
include 'partials/main.php';
require_once '../Starterkit/partials/config.php';
require_once 'partials/get_progress.php';

// Pastikan user login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../Starterkit/auth-login.php');
    exit;
}

// Cek role user untuk greeting dan akses fitur
$role_id = $_SESSION['role']; // id sesuai tabel roles
$nama_role = '';
switch ($role_id) {
    case 1: $nama_role = 'Guru'; break;
    case 2: $nama_role = 'Siswa'; break;
    case 3: $nama_role = 'Admin'; break;
    case 4: $nama_role = 'Kepala Sekolah'; break;
    default: $nama_role = 'User'; break;
}
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
    <?php include 'partials/head-css.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .filter-kelas.active {
            background-color: #4CAF50;
            color: #fff;
            border-color: #4CAF50;
        }
        @media (max-width: 575.98px) {
            .card-header .btn-group,
            .card-header .form-control-sm {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
            .card-header .btn-group .btn {
                width: 50%;
            }
            .card-header .d-flex.flex-wrap.align-items-center.gap-2 {
                flex-direction: column;
                align-items: stretch !important;
            }
        }
    </style>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Greeting -->
                <div class="row mb-3">
                    <div class="col-12">
                        <?php
                        date_default_timezone_set('Asia/Jakarta');
                        $hour = date('H');
                        if ($hour >= 5 && $hour < 12) {
                            $waktu = "SELAMAT PAGI";
                        } elseif ($hour >= 12 && $hour < 15) {
                            $waktu = "SELAMAT SIANG";
                        } elseif ($hour >= 15 && $hour < 18) {
                            $waktu = "SELAMAT SORE";
                        } else {
                            $waktu = "SELAMAT MALAM";
                        }
                        $nama_user = strtoupper($_SESSION['username']);
                        echo "<h4 class='fw-bold'>$waktu, <span class='text-success'>$nama_user</span> <span class='badge bg-info ms-2'>$nama_role</span>!</h4>";
                        echo "<p>Berikut adalah daftar monitoring siswa Anda hari ini.</p>";
                        ?>
                    </div>
                </div>

                <?php
                // Hanya untuk admin & kepala sekolah
                if ($role_id == 3 || $role_id == 4):
                    // Jumlah guru (role_user = 1 pada users)
                    $jml_guru = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) AS total FROM users WHERE role_user = 1"))['total'];
                    // Jumlah siswa
                    $jml_siswa = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(*) AS total FROM siswa"))['total'];
                    // Data jumlah siswa per kelas
                    $data_kelas = [];
                    $result = mysqli_query($link, "SELECT nama_kelas, (SELECT COUNT(*) FROM siswa WHERE kelas_id=kelas.id) AS jumlah FROM kelas ORDER BY nama_kelas");
                    while ($row = mysqli_fetch_assoc($result)) $data_kelas[] = $row;
                ?>
                <div class="row mb-4">
                    <div class="col-md-6 col-12 mb-3 mb-md-0">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width:48px; height:48px;">
                                    <i class="bi bi-person-badge" style="font-size:1.5rem"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Jumlah Guru</h6>
                                    <h3 class="fw-bold mb-0"><?= $jml_guru ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center" style="width:48px; height:48px;">
                                    <i class="bi bi-people" style="font-size:1.5rem"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Jumlah Siswa</h6>
                                    <h3 class="fw-bold mb-0"><?= $jml_siswa ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Grafik siswa per kelas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Grafik Jumlah Siswa per Kelas</h5>
                                <div id="chartSiswaKelas" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                const dataKelas = <?= json_encode($data_kelas) ?>;
                let namaKelas = dataKelas.map(x => x.nama_kelas);
                let jumlah = dataKelas.map(x => Number(x.jumlah));
                var options = {
                    chart: { type: 'bar', height: 250 },
                    series: [{ name: "Jumlah Siswa", data: jumlah }],
                    xaxis: { categories: namaKelas },
                    colors: ['#4CAF50'],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '20%' }},
                    dataLabels: { enabled: true }
                };
                var chart = new ApexCharts(document.querySelector("#chartSiswaKelas"), options);
                chart.render();
                </script>
                <?php endif; ?>

                <!-- Tombol Kelas -->
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $kelas_filter = '';
                            // Guru hanya lihat kelas yang dia pegang, admin/kepala sekolah semua kelas
                            if ($role_id == 1 && !empty($_SESSION['kelas_ids'])) {
                                $kelas_in = implode(",", array_map('intval', $_SESSION['kelas_ids']));
                                $kelas_filter = " WHERE k.id IN ($kelas_in) ";
                            }
                            $kelas_sql = "
                                SELECT 
                                    k.id AS kelas_id,
                                    k.nama_kelas,
                                    GROUP_CONCAT(u.username SEPARATOR ', ') AS nama_guru
                                FROM 
                                    kelas k
                                LEFT JOIN 
                                    mapping_guru_kelas mgk ON k.id = mgk.kelas_id
                                LEFT JOIN 
                                    users u ON mgk.guru_id = u.id
                                $kelas_filter
                                GROUP BY 
                                    k.id
                                ORDER BY 
                                    k.nama_kelas ASC
                            ";
                            $kelas_result = mysqli_query($link, $kelas_sql);
                            if (!$kelas_result) {
                                die('QUERY ERROR: ' . mysqli_error($link));
                            }
                            while ($kelas_row = mysqli_fetch_assoc($kelas_result)) {
                                echo "<div class='text-center'>";
                                echo "<button class='btn btn-outline-primary filter-kelas' data-kelas-id='" . htmlspecialchars($kelas_row['kelas_id']) . "'>" . htmlspecialchars($kelas_row['nama_kelas']) . "</button>";
                                echo "<p class='text-muted small'>Guru: " . ($kelas_row['nama_guru'] ? htmlspecialchars($kelas_row['nama_guru']) : 'Belum ada guru') . "</p>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Tabel Progress + Tombol Download Rekap -->
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <h3 class="card-title mb-2 mb-md-0">Progress Siswa</h3>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <input type="date" id="rekapStart" class="form-control form-control-sm" style="width: 120px;">
                            <span class="d-none d-md-inline">s/d</span>
                            <input type="date" id="rekapEnd" class="form-control form-control-sm" style="width: 120px;">
                            <div class="btn-group ms-0 ms-md-2" role="group">
                                <button type="button" class="btn btn-success btn-sm" id="btnDownloadRekapExcel">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="btnDownloadRekapPDF">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-md">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Nama Guru</th>
                                        <th>Kegiatan Tepat Waktu</th>
                                        <th>Progress</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody id="progressTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center">Silakan pilih kelas untuk melihat data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include 'partials/footer.php'; ?>

                <!-- Bootstrap Toast -->
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                  <div id="customToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                      <div class="toast-body">
                        <span id="toastMessage">Silakan pilih rentang tanggal.</span>
                      </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                  </div>
                </div>
                <!-- End Bootstrap Toast -->

            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Kegiatan Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="filterDate" class="form-label">Filter Tanggal</label>
                    <input type="date" id="filterDate" class="form-control">
                </div>
                <div id="detailContent">
                    <p class="text-center">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="partials/verifikasi_checkpoint.php">
        <div class="modal-header">
          <h5 class="modal-title">Alasan Penolakan Checkpoint</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="tolak-id">
          <input type="hidden" name="aksi" value="tolak">
          <div class="mb-3">
            <label>Alasan</label>
            <textarea class="form-control" name="alasan_tolak" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
          <button class="btn btn-danger" type="submit">Tolak</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="assets/js/pages/dashboard.init.js"></script>
<script src="assets/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterDateInput = document.getElementById('filterDate');
    const detailContent = document.getElementById('detailContent');
    const kelasButtons = document.querySelectorAll('.filter-kelas');
    const progressTableBody = document.getElementById('progressTableBody');

    function attachDetailListeners() {
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const siswaId = this.getAttribute('data-siswa-id');
                filterDateInput.value = '';
                loadDetails(siswaId);
                filterDateInput.addEventListener('change', function() {
                    loadDetails(siswaId, this.value);
                });
            });
        });
    }

    function loadDetails(siswaId, tanggal = null) {
        detailContent.innerHTML = '<p class="text-center">Memuat data...</p>';
        let url = `partials/get_kegiatan.php?siswa_id=${siswaId}`;
        if (tanggal) url += `&tanggal=${tanggal}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    detailContent.innerHTML = `<p class="text-danger text-center">${data.error}</p>`;
                } else {
                    let html = `
<table class="table table-bordered" id="kegiatanTable">
<thead>
<tr>
  <th>Nama Kegiatan</th>
  <th>Jam Mulai</th>
  <th>Jam Selesai</th>
  <th>Waktu Checkpoint</th>
  <th>Status</th>
  <th>Status Verifikasi</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
`;
                    data.forEach(k => {
                        let statusVerifikasi = '';
                        if (k.status === 'Belum Checkpoint' || k.status_verifikasi === '-') {
                            statusVerifikasi = `<span class="badge bg-secondary">-</span>`;
                        } else if (k.status_verifikasi === 'pending') {
                            statusVerifikasi = `<span class="badge bg-warning text-dark">Pending</span>`;
                        } else if (k.status_verifikasi === 'disetujui') {
                            statusVerifikasi = `<span class="badge bg-success">Disetujui</span>`;
                        } else if (k.status_verifikasi === 'ditolak') {
                            statusVerifikasi = `<span class="badge bg-danger">Ditolak</span>`;
                            if (k.alasan_tolak) statusVerifikasi += `<br><small class="text-danger">Alasan: ${k.alasan_tolak}</small>`;
                        } else {
                            statusVerifikasi = `<span class="badge bg-secondary">-</span>`;
                        }

                        let aksi = '';
                        if (k.status_verifikasi === 'pending' && k.status !== 'Belum Checkpoint') {
                            aksi = `
        <form method="post" action="partials/verifikasi_checkpoint.php" class="d-inline">
            <input type="hidden" name="id" value="${k.checkpoint_id}">
            <input type="hidden" name="aksi" value="setujui">
            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
        </form>
        <button class="btn btn-danger btn-sm btn-tolak" data-id="${k.checkpoint_id}" data-bs-toggle="modal" data-bs-target="#modalTolak">Tolak</button>
        `;
                        } else {
                            aksi = `<span class="text-muted">-</span>`;
                        }

                        html += `
    <tr>
      <td>${k.nama_kegiatan}</td>
      <td>${k.jam_mulai}</td>
      <td>${k.jam_selesai}</td>
      <td>${k.waktu_checkpoint}</td>
      <td>${k.status}</td>
      <td>${statusVerifikasi}</td>
      <td>${aksi}</td>
    </tr>
    `;
                    });
                    html += '</tbody></table>';
                    detailContent.innerHTML = html;
                }
            })
            .catch(error => {
                detailContent.innerHTML = `<p class="text-danger text-center">Terjadi kesalahan: ${error.message}</p>`;
            });
    }

    kelasButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedKelasId = this.getAttribute('data-kelas-id');
            kelasButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            progressTableBody.innerHTML = '<tr><td colspan="5" class="text-center">Memuat data...</td></tr>';
            fetch(`partials/get_progress_by_kelas.php?kelas_id=${selectedKelasId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        progressTableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${data.error}</td></tr>`;
                    } else {
                        let html = '';
                        data.forEach(row => {
                            html += `
                                <tr>
                                    <td class="align-middle">${row.nama_siswa}</td>
                                    <td class="align-middle">${row.nama_guru || 'Belum ada guru'}</td>
                                    <td class="align-middle">${row.kegiatan_tepat_waktu} / ${row.total_kegiatan}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">${row.progress}%</span>
                                            <div class="progress progress-sm flex-grow-1">
                                                <div class="progress-bar bg-primary" style="width: ${row.progress}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-info btn-sm view-details" data-siswa-id="${row.siswa_id}" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>
                                    </td>
                                </tr>`;
                        });
                        progressTableBody.innerHTML = html;
                        attachDetailListeners();
                    }
                })
                .catch(error => {
                    progressTableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan: ${error.message}</td></tr>`;
                });
        });
    });

    attachDetailListeners();

    // Tombol Download Rekap Excel Perkelas
    document.getElementById('btnDownloadRekapExcel').addEventListener('click', function () {
        const activeBtn = document.querySelector('.filter-kelas.active');
        if (!activeBtn) {
            showToast("Silakan pilih kelas terlebih dahulu!");
            return;
        }
        const kelasId = activeBtn.getAttribute('data-kelas-id');
        const start = document.getElementById('rekapStart').value;
        const end = document.getElementById('rekapEnd').value;

        if (!start || !end) {
            showToast('Silakan pilih rentang tanggal.');
            return;
        }
        window.open(`rekap_kelas_excel.php?kelas_id=${kelasId}&start=${start}&end=${end}`, '_blank');
    });

    // Tombol Download Rekap PDF Perkelas
    document.getElementById('btnDownloadRekapPDF').addEventListener('click', function () {
        const activeBtn = document.querySelector('.filter-kelas.active');
        if (!activeBtn) {
            showToast("Silakan pilih kelas terlebih dahulu!");
            return;
        }
        const kelasId = activeBtn.getAttribute('data-kelas-id');
        const start = document.getElementById('rekapStart').value;
        const end = document.getElementById('rekapEnd').value;

        if (!start || !end) {
            showToast('Silakan pilih rentang tanggal.');
            return;
        }
        window.open(`rekap_kelas_pdf.php?kelas_id=${kelasId}&tgl_mulai=${start}&tgl_selesai=${end}`, '_blank');
    });

    // Fungsi Bootstrap Toast
    function showToast(message) {
        document.getElementById('toastMessage').textContent = message;
        var toastEl = document.getElementById('customToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});

// Modal Tolak
document.addEventListener('click', function(e){
    if (e.target.classList.contains('btn-tolak')) {
        let id = e.target.getAttribute('data-id');
        document.getElementById('tolak-id').value = id;
    }
});
</script>
</body>
</html>
