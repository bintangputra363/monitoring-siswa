<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

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
                            <h4 class="fs-16 fw-semibold mb-1">Kegiatan Siswa</h4>
                            <?php if ($_SESSION['role'] != 1) { ?>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKegiatanModal">Tambah Kegiatan</button>
                            <?php } ?>
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
                                                <th style="width: 5%;">No</th>
                                                <th style="width: 20%;">Nama Kegiatan</th>
                                                <th style="width: 30%;">Deskripsi</th>
                                                <th style="width: 15%;">Jam Mulai</th>
                                                <th style="width: 15%;">Jam Selesai</th>
                                                <?php if ($_SESSION['role'] != 1) { ?>
                                                    <th style="width: 15%;">Aksi</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once '../Starterkit/partials/config.php';
                                            $sql = "SELECT id, nama_kegiatan, deskripsi, jam_mulai, jam_selesai FROM kegiatan ORDER BY jam_mulai ASC";
                                            $result = mysqli_query($link, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kegiatan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jam_selesai']) . "</td>";
                                                    if ($_SESSION['role'] != 1) {
                                                        echo "<td>
                                                            <button class='btn btn-warning btn-sm' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#editKegiatanModal' 
                                                                    data-id='" . $row['id'] . "' 
                                                                    data-nama='" . htmlspecialchars($row['nama_kegiatan']) . "' 
                                                                    data-deskripsi='" . htmlspecialchars($row['deskripsi']) . "' 
                                                                    data-jam-mulai='" . htmlspecialchars($row['jam_mulai']) . "' 
                                                                    data-jam-selesai='" . htmlspecialchars($row['jam_selesai']) . "'>
                                                                Edit
                                                            </button>
                                                            <button class='btn btn-danger btn-sm' 
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#deleteKegiatanModal' 
                                                                    data-id='" . $row['id'] . "'>
                                                                Hapus
                                                            </button>
                                                          </td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            } else {
                                                $colspan = $_SESSION['role'] != 1 ? 6 : 5;
                                                echo "<tr><td colspan='$colspan' class='text-center'>Tidak ada data kegiatan.</td></tr>";
                                            }
                                            mysqli_close($link);
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
        <?php if ($_SESSION['role'] != 1) { ?>
        <div class="modal fade" id="addKegiatanModal" tabindex="-1" aria-labelledby="addKegiatanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addKegiatanForm" action="partials/add_kegiatan.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addKegiatanModalLabel">Tambah Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" id="namaKegiatan" name="nama_kegiatan" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsiKegiatan" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsiKegiatan" name="deskripsi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jamKegiatan" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="jamKegiatan" name="jam_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="jamSelesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="jamSelesai" name="jam_selesai" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitButton">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Modal Edit Kegiatan -->
        <?php if ($_SESSION['role'] != 1) { ?>
        <div class="modal fade" id="editKegiatanModal" tabindex="-1" aria-labelledby="editKegiatanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editKegiatanForm" action="partials/edit_kegiatan.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKegiatanModalLabel">Edit Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editId" name="id">
                            <div class="mb-3">
                                <label for="editNamaKegiatan" class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" id="editNamaKegiatan" name="nama_kegiatan" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDeskripsiKegiatan" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="editDeskripsiKegiatan" name="deskripsi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editJamKegiatan" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="editJamKegiatan" name="jam_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="editJamSelesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="editJamSelesai" name="jam_selesai" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Modal Hapus Kegiatan -->
        <?php if ($_SESSION['role'] != 1) { ?>
        <div class="modal fade" id="deleteKegiatanModal" tabindex="-1" aria-labelledby="deleteKegiatanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="deleteKegiatanForm" action="partials/delete_kegiatan.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteKegiatanModalLabel">Hapus Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus kegiatan ini?</p>
                            <input type="hidden" id="deleteId" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<?php include 'partials/vendor-scripts.php'; ?>

<script src="assets/js/app.js"></script>
<?php if ($_SESSION['role'] != 1) { ?>
<script>
    document.getElementById('addKegiatanForm').addEventListener('submit', function(event) {
        let isValid = true;
        const inputs = this.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        const jamMulai = this.querySelector('#jamKegiatan').value;
        const jamSelesai = this.querySelector('#jamSelesai').value;
        if (!isValid) {
            event.preventDefault();
        }
    });

    document.getElementById('editKegiatanForm').addEventListener('submit', function(event) {
        let isValid = true;
        const jamMulai = this.querySelector('#editJamKegiatan').value;
        const jamSelesai = this.querySelector('#editJamSelesai').value;
        if (jamMulai && jamSelesai && jamMulai >= jamSelesai) {
            isValid = false;
            alert('Jam Selesai harus lebih besar dari Jam Mulai.');
        }
        if (!isValid) {
            event.preventDefault();
        }
    });

    const editModal = document.getElementById('editKegiatanModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const deskripsi = button.getAttribute('data-deskripsi');
        const jamMulai = button.getAttribute('data-jam-mulai');
        const jamSelesai = button.getAttribute('data-jam-selesai');
        editModal.querySelector('#editId').value = id;
        editModal.querySelector('#editNamaKegiatan').value = nama;
        editModal.querySelector('#editDeskripsiKegiatan').value = deskripsi;
        editModal.querySelector('#editJamKegiatan').value = jamMulai;
        editModal.querySelector('#editJamSelesai').value = jamSelesai;
    });

    const deleteModal = document.getElementById('deleteKegiatanModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        deleteModal.querySelector('#deleteId').value = id;
    });
</script>
<?php } ?>
</body>
</html>
