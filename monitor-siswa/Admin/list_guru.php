<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php require_once '../Starterkit/partials/config.php'; ?>

<?php
$sql = "
    SELECT 
        u.id AS guru_id,
        u.username AS nama_guru,
        GROUP_CONCAT(k.nama_kelas SEPARATOR ', ') AS kelas
    FROM 
        users u
    LEFT JOIN 
        mapping_guru_kelas mgk ON u.id = mgk.guru_id
    LEFT JOIN 
        kelas k ON mgk.kelas_id = k.id
    WHERE 
        u.role_user = 1
    GROUP BY 
        u.id
    ORDER BY 
        u.username ASC
";
$result = mysqli_query($link, $sql);

if (!$result) {
    die('Query gagal: ' . mysqli_error($link));
}
?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Kegiatan Siswa')); ?>
    <?php include 'partials/head-css.php'; ?>
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
                            <h4 class="fs-16 fw-semibold mb-1">Mapping Guru</h4>
                             <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMappingModal">Mapping Guru</button>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                              <div class="table-responsive-md">
                                <table class="table text-nowrap mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_guru']) . "</td>";
                    echo "<td>" . ($row['kelas'] ? htmlspecialchars($row['kelas']) : 'Belum ada kelas') . "</td>";
                    echo "<td>
                            <button class='btn btn-warning btn-sm' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#editMappingModal' 
                                    data-id='" . $row['guru_id'] . "' 
                                    data-nama='" . htmlspecialchars($row['nama_guru']) . "' 
                                    data-kelas='" . htmlspecialchars($row['kelas']) . "'>
                                Edit
                            </button>
                            <button class='btn btn-danger btn-sm' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#deleteGuruModal' 
                                    data-id='" . $row['guru_id'] . "'>
                                Hapus
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Tidak ada data guru.</td></tr>";
            }
            ?>
        </tbody>
    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Table -->
            </div>
        </div>

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>

<script src="assets/js/app.js"></script>

<div class="modal fade" id="addMappingModal" tabindex="-1" aria-labelledby="addMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/add_mapping_guru.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMappingModalLabel">Mapping Guru ke Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="guru" class="form-label">Pilih Guru</label>
                        <select name="guru_id" id="guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php
                            $guruQuery = "SELECT id, username FROM users WHERE role_user = 1 ORDER BY username ASC";
                            $guruResult = mysqli_query($link, $guruQuery);
                            while ($guruRow = mysqli_fetch_assoc($guruResult)) {
                                echo "<option value='" . $guruRow['id'] . "'>" . htmlspecialchars($guruRow['username']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Pilih Kelas</label>
                        <select name="kelas_id[]" id="kelas" class="form-select" multiple required>
                            <?php
                            $kelasQuery = "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                            $kelasResult = mysqli_query($link, $kelasQuery);
                            while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                                echo "<option value='" . $kelasRow['id'] . "'>" . htmlspecialchars($kelasRow['nama_kelas']) . "</option>";
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Tekan <kbd>Ctrl</kbd> untuk memilih lebih dari satu kelas.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editMappingModal" tabindex="-1" aria-labelledby="editMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/edit_mapping_guru.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMappingModalLabel">Edit Mapping Guru ke Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editGuruId" name="guru_id">
                    <div class="mb-3">
                        <label for="editGuruNama" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="editGuruNama" name="guru_nama" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editKelas" class="form-label">Pilih Kelas</label>
                        <select name="kelas_id[]" id="editKelas" class="form-select" multiple required>
                            <?php
                            $kelasQuery = "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                            $kelasResult = mysqli_query($link, $kelasQuery);
                            while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                                echo "<option value='" . $kelasRow['id'] . "'>" . htmlspecialchars($kelasRow['nama_kelas']) . "</option>";
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Tekan <kbd>Ctrl</kbd> untuk memilih lebih dari satu kelas.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteGuruModal" tabindex="-1" aria-labelledby="deleteGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/delete_mapping_guru.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGuruModalLabel">Hapus Mapping Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus mapping guru ini?</p>
                    <input type="hidden" id="deleteGuruId" name="guru_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editMappingModal = document.getElementById('editMappingModal');

        // Event listener untuk mengisi modal edit
        editMappingModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const guruId = button.getAttribute('data-id'); // Ambil ID guru
            const guruNama = button.getAttribute('data-nama'); // Ambil nama guru
            const kelas = button.getAttribute('data-kelas'); // Ambil kelas (dipisahkan koma)

            // Isi data ke dalam modal
            editMappingModal.querySelector('#editGuruId').value = guruId;
            editMappingModal.querySelector('#editGuruNama').value = guruNama;

            // Set nilai dropdown kelas
            const kelasDropdown = editMappingModal.querySelector('#editKelas');
            const kelasArray = kelas.split(', '); // Pisahkan kelas menjadi array
            Array.from(kelasDropdown.options).forEach(option => {
                option.selected = kelasArray.includes(option.text);
            });
        });

        const deleteGuruModal = document.getElementById('deleteGuruModal');

        // Event listener untuk mengisi modal delete
        deleteGuruModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const guruId = button.getAttribute('data-id'); // Ambil ID guru

            // Isi data ke dalam modal
            deleteGuruModal.querySelector('#deleteGuruId').value = guruId;
        });
    });
</script>
</body>
</html>