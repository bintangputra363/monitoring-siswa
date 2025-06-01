<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php require_once '../Starterkit/partials/config.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Mapping Siswa')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>

<?php include 'partials/body.php'; ?>

<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h4 class="fs-16 fw-semibold mb-1">Mapping Siswa</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMappingModal">Tambah Mapping Siswa</button>
                    </div>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                                    unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Filter Kelas -->
                <form method="GET" class="my-3">
                    <label for="filter_kelas">Pilih Kelas:</label>
                    <select name="kelas_id" id="filter_kelas" class="form-select w-auto d-inline-block mx-2">
                        <option value="">Semua Kelas</option>
                        <?php
                        $kelas_query = mysqli_query($link, "SELECT * FROM kelas");
                        while ($kelas = mysqli_fetch_assoc($kelas_query)) {
                            $selected = (isset($_GET['kelas_id']) && $_GET['kelas_id'] == $kelas['id']) ? 'selected' : '';
                            echo "<option value='{$kelas['id']}' $selected>" . htmlspecialchars($kelas['nama_kelas']) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">Tampilkan</button>
                </form>

                <!-- Tabel Mapping -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive-md">
                                    <table class="table table-striped text-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $where = "";
                                            if (isset($_GET['kelas_id']) && $_GET['kelas_id'] !== '') {
                                                $kelas_id = mysqli_real_escape_string($link, $_GET['kelas_id']);
                                                $where = "WHERE s.kelas_id = '$kelas_id'";
                                            }

                                            $sql = "SELECT m.id, s.nama_siswa, k.nama_kegiatan, kl.nama_kelas 
                                                    FROM mapping_siswa_kegiatan m
                                                    JOIN siswa s ON m.siswa_id = s.id
                                                    JOIN kegiatan k ON m.kegiatan_id = k.id
                                                    JOIN kelas kl ON s.kelas_id = kl.id
                                                    $where
                                                    ORDER BY kl.nama_kelas, s.nama_siswa";

                                            $result = mysqli_query($link, $sql);
                                            if (mysqli_num_rows($result) > 0) {
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_siswa']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kelas']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kegiatan']) . "</td>";
                                                    echo "<td><button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteMappingModal' data-id='" . $row['id'] . "'>Hapus</button></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data mapping.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah Mapping -->
                <!-- Modal Tambah Mapping -->
                <div class="modal fade" id="addMappingModal" tabindex="-1" aria-labelledby="addMappingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addMappingForm" action="partials/add_mapping.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Mapping Siswa ke Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Dropdown Pilih Kelas -->
                                    <div class="mb-3">
                                        <label for="selectKelas" class="form-label">Pilih Kelas</label>
                                        <select class="form-select" id="selectKelas" name="kelas_id" required>
                                            <option value="" disabled selected>Pilih Kelas</option>
                                            <?php
                                            $kelas_result = mysqli_query($link, "SELECT * FROM kelas ORDER BY nama_kelas");
                                            while ($kelas = mysqli_fetch_assoc($kelas_result)) {
                                                echo "<option value='{$kelas['id']}'>" . htmlspecialchars($kelas['nama_kelas']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Dropdown Pilih Siswa (berubah berdasarkan kelas) -->
                                    <div class="mb-3">
                                        <label for="selectSiswa" class="form-label">Pilih Siswa</label>
                                        <select class="form-select" id="selectSiswa" name="siswa_id" required>
                                            <option value="" disabled selected>Pilih Kelas Terlebih Dahulu</option>
                                        </select>
                                    </div>

                                    <!-- Dropdown Pilih Kegiatan -->
                                    <div class="mb-3">
                                        <label for="selectKegiatan" class="form-label">Pilih Kegiatan</label>
                                        <select class="form-select" id="selectKegiatan" name="kegiatan_id[]" multiple required>
                                            <?php
                                            $kegiatan_sql = "SELECT id, nama_kegiatan FROM kegiatan";
                                            $kegiatan_result = mysqli_query($link, $kegiatan_sql);
                                            while ($kegiatan = mysqli_fetch_assoc($kegiatan_result)) {
                                                echo "<option value='" . htmlspecialchars($kegiatan['id']) . "'>" . htmlspecialchars($kegiatan['nama_kegiatan']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <small class="text-muted">Tekan <kbd>Ctrl</kbd> atau <kbd>Shift</kbd> untuk memilih lebih dari satu kegiatan.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Mapping</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Modal Hapus Mapping -->
                <div class="modal fade" id="deleteMappingModal" tabindex="-1" aria-labelledby="deleteMappingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="partials/delete_mapping.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Mapping</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin menghapus mapping ini?</p>
                                    <input type="hidden" name="id" id="deleteMappingId">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php include 'partials/footer.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>
<script src="assets/js/app.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteMappingModal = document.getElementById('deleteMappingModal');
        deleteMappingModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            document.getElementById('deleteMappingId').value = id;
        });
    });
    document.getElementById('selectKelas').addEventListener('change', function() {
        var kelasId = this.value;
        var siswaSelect = document.getElementById('selectSiswa');

        siswaSelect.innerHTML = '<option>Loading...</option>';

        fetch('partials/get_siswa_by_kelas.php?kelas_id=' + kelasId)
            .then(response => response.json())
            .then(data => {
                siswaSelect.innerHTML = '<option value="" disabled selected>Pilih Siswa</option>';
                data.forEach(function(siswa) {
                    var option = document.createElement('option');
                    option.value = siswa.id;
                    option.textContent = siswa.nama_siswa;
                    siswaSelect.appendChild(option);
                });
            })
            .catch(error => {
                siswaSelect.innerHTML = '<option value="">Gagal memuat siswa</option>';
                console.error('Error:', error);
            });
    });
</script>
</body>

</html>