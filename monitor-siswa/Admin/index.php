<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php require_once '../Starterkit/partials/config.php'; ?>
<?php require_once 'partials/get_progress.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
    <?php include 'partials/head-css.php'; ?>
    <style>
        .filter-kelas.active {
            background-color: #4CAF50;
            color: #fff;
            border-color: #4CAF50;
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
                <div class="row mb-4">
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
                        echo "<h4 class='fw-bold'>$waktu, <span class='text-success'>$nama_user!</span></h4>";
                        echo "<p>Berikut adalah daftar monitoring siswa Anda hari ini.</p>";
                        ?>
                    </div>
                </div>

                <!-- Tombol Kelas -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $kelas_filter = '';
                            if ($_SESSION['role'] == 1 && !empty($_SESSION['kelas_ids'])) {
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

                <!-- Tabel Progress -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Progress Siswa</h3>
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
                        let html = '<table class="table table-bordered"><thead><tr><th>Nama Kegiatan</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Waktu Checkpoint</th><th>Status</th></tr></thead><tbody>';
                        data.forEach(k => {
                            html += `<tr><td>${k.nama_kegiatan}</td><td>${k.jam_mulai}</td><td>${k.jam_selesai}</td><td>${k.waktu_checkpoint}</td><td>${k.status}</td></tr>`;
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
                                        <td class="align-middle">${row.kegiatan_tepat_waktu} / 7</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">${Math.round(row.progress * 100) / 100}%</span>
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
    });
</script>
</body>
</html>
