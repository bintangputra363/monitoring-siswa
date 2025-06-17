<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php require_once '../Starterkit/partials/config.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Kegiatan Siswa')); ?>
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
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="fs-16 fw-semibold mb-1">Daftar Siswa</h4>
                            <?php
                            // Guru hanya bisa tambah siswa ke kelas yang dia pegang
                            $allowTambah = true;
                            if ($_SESSION['role'] == 1 && empty($_SESSION['kelas_ids'])) {
                                $allowTambah = false;
                            }
                            if ($allowTambah) { ?>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSiswaModal">Tambah Siswa</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            // Jika guru, hanya tampilkan kelas yang dia pegang
                            if ($_SESSION['role'] == 1 && !empty($_SESSION['kelas_ids'])) {
                                $kelas_in = implode(",", array_map('intval', $_SESSION['kelas_ids']));
                                $kelas_sql = "SELECT id, nama_kelas FROM kelas WHERE id IN ($kelas_in) ORDER BY nama_kelas ASC";
                            } else {
                                $kelas_sql = "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                            }
                            $kelas_result = mysqli_query($link, $kelas_sql);

                            if (mysqli_num_rows($kelas_result) > 0) {
                                while ($kelas_row = mysqli_fetch_assoc($kelas_result)) {
                                    echo "<button class='btn btn-outline-primary filter-kelas' data-kelas-id='" . $kelas_row['id'] . "'>" . htmlspecialchars($kelas_row['nama_kelas']) . "</button>";
                                }
                            } else {
                                echo "<p>Tidak ada kelas tersedia.</p>";
                            }
                            ?>
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
                                    <table class="table text-nowrap mb-0" id="siswaTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">No</th>
                                                <th style="width: 20%;">Nama Siswa</th>
                                                <th style="width: 15%;">Kelas</th>
                                                <th style="width: 20%;">Username</th>
                                                <th style="width: 20%;">Email</th>
                                                <th style="width: 20%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data siswa akan dimuat di sini melalui JavaScript -->
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

        <!-- Modal Tambah Siswa -->
        <div class="modal fade" id="addSiswaModal" tabindex="-1" aria-labelledby="addSiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addSiswaForm" action="partials/add_siswa.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSiswaModalLabel">Tambah Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="namaSiswa" class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" id="namaSiswa" name="nama_siswa" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelasSiswa" class="form-label">Kelas</label>
                                <select class="form-select" id="kelasSiswa" name="kelas_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    // Filter kelas yang bisa dipilih
                                    if ($_SESSION['role'] == 1 && !empty($_SESSION['kelas_ids'])) {
                                        $kelas_in = implode(",", array_map('intval', $_SESSION['kelas_ids']));
                                        $kelasQuery = "SELECT id, nama_kelas FROM kelas WHERE id IN ($kelas_in) ORDER BY nama_kelas ASC";
                                    } else {
                                        $kelasQuery = "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                                    }
                                    $kelasResult = mysqli_query($link, $kelasQuery);
                                    while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                                        echo "<option value='" . $kelasRow['id'] . "'>" . htmlspecialchars($kelasRow['nama_kelas']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="usernameSiswa" class="form-label">Username</label>
                                <input type="text" class="form-control" id="usernameSiswa" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailSiswa" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailSiswa" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="passwordSiswa" class="form-label">Password</label>
                                <input type="password" class="form-control" id="passwordSiswa" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Siswa -->
        <div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editSiswaForm" action="partials/edit_siswa.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSiswaModalLabel">Edit Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editUserId" name="user_id">
                            <div class="mb-3">
                                <label for="editNamaSiswa" class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" id="editNamaSiswa" name="nama_siswa" required>
                            </div>
                            <div class="mb-3">
                                <label for="editKelasSiswa" class="form-label">Kelas</label>
                                <select class="form-select" id="editKelasSiswa" name="kelas_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    if ($_SESSION['role'] == 1 && !empty($_SESSION['kelas_ids'])) {
                                        $kelas_in = implode(",", array_map('intval', $_SESSION['kelas_ids']));
                                        $kelasQuery = "SELECT id, nama_kelas FROM kelas WHERE id IN ($kelas_in) ORDER BY nama_kelas ASC";
                                    } else {
                                        $kelasQuery = "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
                                    }
                                    $kelasResult = mysqli_query($link, $kelasQuery);
                                    while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                                        echo "<option value='" . $kelasRow['id'] . "'>" . htmlspecialchars($kelasRow['nama_kelas']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editUsernameSiswa" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsernameSiswa" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmailSiswa" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmailSiswa" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPasswordSiswa" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control" id="editPasswordSiswa" name="password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Siswa -->
        <div class="modal fade" id="deleteSiswaModal" tabindex="-1" aria-labelledby="deleteSiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="deleteSiswaForm" action="partials/delete_siswa.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSiswaModalLabel">Hapus Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus siswa ini?</p>
                            <input type="hidden" id="deleteUserId" name="user_id">
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

<?php include 'partials/vendor-scripts.php'; ?>

<script src="assets/js/app.js"></script>
<script>
    // Modal Edit Siswa
    const editSiswaModal = document.getElementById('editSiswaModal');
    editSiswaModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const nama = button.getAttribute('data-nama');
        const kelasId = button.getAttribute('data-kelas-id');
        const username = button.getAttribute('data-username');
        const email = button.getAttribute('data-email');

        editSiswaModal.querySelector('#editUserId').value = userId;
        editSiswaModal.querySelector('#editNamaSiswa').value = nama;
        editSiswaModal.querySelector('#editKelasSiswa').value = kelasId;
        editSiswaModal.querySelector('#editUsernameSiswa').value = username;
        editSiswaModal.querySelector('#editEmailSiswa').value = email;
    });

    // Modal Hapus Siswa
    const deleteSiswaModal = document.getElementById('deleteSiswaModal');
    deleteSiswaModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        deleteSiswaModal.querySelector('#deleteUserId').value = userId;
    });

    // Filter Kelas dan Tampilkan Data Siswa
    document.querySelectorAll('.filter-kelas').forEach(button => {
        button.addEventListener('click', function() {
            const kelasId = this.getAttribute('data-kelas-id');
            document.querySelectorAll('.filter-kelas').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            fetch(`partials/get_siswa.php?kelas_id=${kelasId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#siswaTable tbody');
                    tbody.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach((siswa, index) => {
                            // Jika guru, pastikan hanya siswa kelas yang dia pegang yang bisa di-edit/hapus
                            let actionBtn = '';
                            <?php if ($_SESSION['role'] == 1) { ?>
                                const allowedKelas = <?php echo json_encode($_SESSION['kelas_ids'] ?? []); ?>;
                                if (allowedKelas.includes(parseInt(siswa.kelas_id))) {
                                    actionBtn = `
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiswaModal"
                                            data-user-id="${siswa.user_id}" data-nama="${siswa.nama_siswa}" data-kelas-id="${siswa.kelas_id}"
                                            data-username="${siswa.username}" data-email="${siswa.useremail}">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSiswaModal"
                                            data-user-id="${siswa.user_id}">Hapus</button>
                                    `;
                                } else {
                                    actionBtn = `<span class="text-muted">-</span>`;
                                }
                            <?php } else { ?>
                                actionBtn = `
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiswaModal"
                                        data-user-id="${siswa.user_id}" data-nama="${siswa.nama_siswa}" data-kelas-id="${siswa.kelas_id}"
                                        data-username="${siswa.username}" data-email="${siswa.useremail}">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSiswaModal"
                                        data-user-id="${siswa.user_id}">Hapus</button>
                                `;
                            <?php } ?>

                            tbody.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${siswa.nama_siswa}</td>
                                    <td>${siswa.nama_kelas}</td>
                                    <td>${siswa.username}</td>
                                    <td>${siswa.useremail}</td>
                                    <td>${actionBtn}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data siswa.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data siswa.');
                });
        });
    });
</script>
</body>
</html>
