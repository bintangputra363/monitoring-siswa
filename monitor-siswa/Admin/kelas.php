<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php
require_once '../Starterkit/partials/config.php';

// Query untuk mengambil data kelas
$sql = "SELECT * FROM kelas ORDER BY nama_kelas ASC";
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
                            <h4 class="card-title">Daftar Kelas</h4>
                            <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addKelasModal">Tambah Kelas</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="table-responsive-md">
                                    <table class="table text-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kelas</th>
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
                                                    echo "<td>" . htmlspecialchars($row['nama_kelas']) . "</td>";
                                                    echo "<td>
                                                            <button class='btn btn-warning btn-sm' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#editKelasModal' 
                                                                    data-id='" . $row['id'] . "' 
                                                                    data-nama='" . htmlspecialchars($row['nama_kelas']) . "'>
                                                                Edit
                                                            </button>
                                                            <button class='btn btn-danger btn-sm' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#deleteKelasModal' 
                                                                    data-id='" . $row['id'] . "'>
                                                                Hapus
                                                            </button>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3' class='text-center'>Tidak ada data kelas.</td></tr>";
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

        <!-- Modal Tambah Kegiatan -->

        <!-- Modal Edit Kegiatan -->

        <!-- Modal Hapus Kegiatan -->

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>

<script src="assets/js/app.js"></script>

<div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/kelas_add.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaKelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="namaKelas" name="nama_kelas" required>
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

<div class="modal fade" id="editKelasModal" tabindex="-1" aria-labelledby="editKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/kelas_edit.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKelasModalLabel">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editKelasId">
                    <div class="mb-3">
                        <label for="editNamaKelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="editNamaKelas" name="nama_kelas" required>
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

<div class="modal fade" id="deleteKelasModal" tabindex="-1" aria-labelledby="deleteKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="partials/kelas_delete.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKelasModalLabel">Hapus Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="deleteKelasId">
                    <p>Apakah Anda yakin ingin menghapus kelas ini?</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const editKelasModal = document.getElementById('editKelasModal');
        const deleteKelasModal = document.getElementById('deleteKelasModal');

        // Isi modal edit
        editKelasModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            editKelasModal.querySelector('#editKelasId').value = id;
            editKelasModal.querySelector('#editNamaKelas').value = nama;
        });

        // Isi modal hapus
        deleteKelasModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            deleteKelasModal.querySelector('#deleteKelasId').value = id;
        });
    });
</script>

</body>

</html>