<!-- Start topbar -->
<header id="page-topbar">
    <div class="navbar-header px-2 py-2 d-flex align-items-center justify-content-between" style="min-height:56px;">
        <!-- KIRI: Logo + Hamburger + Judul -->
        <div class="d-flex align-items-center" style="gap:10px;min-width:0;">
            <!-- Hamburger (selalu kiri di mobile, sembunyi di desktop) -->
            <button type="button" class="btn btn-sm top-icon sidebar-btn d-inline-flex d-md-none align-items-center justify-content-center"
                    id="sidebar-btn" aria-label="Menu"
                    style="padding:7px 11px; border-radius: 7px; margin-right: 30px;">
                <i class="mdi mdi-menu align-middle fs-20"></i>
            </button>
            <!-- Logo SMP -->
            <img src="assets/images/logoSMP.webp" alt="Logo SMP"
                style="height:38px;max-width:40px;width:auto;object-fit:contain;">
            <!-- Judul -->
            <div class="d-flex flex-column" style="min-width:0;">
                <span class="fw-bold text-success logo-text" style="font-size:15px;line-height:1;white-space:nowrap;">
                    MONITOR SISWA
                </span>
                <span class="text-success" style="font-size:12px;line-height:1;">
                    SMPN 4 Kota Tangerang
                </span>
            </div>
        </div>

        <!-- KANAN: Avatar/Profile -->
        <div class="d-flex align-items-center" style="gap:13px;">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm top-icon p-0" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        style="padding:0; border:none; background:transparent;">
                    <img class="rounded avatar-2xs p-0" src="assets/images/users/avatar-6.png" alt="Header Avatar" style="width:36px; height:36px;">
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated overflow-hidden py-0">
                    <div class="card border-0">
                        <div class="card-header bg-primary rounded-0">
                            <div class="rich-list-item w-100 p-0">
                                <div class="rich-list-prepend">
                                    <div class="avatar avatar-label-light avatar-circle">
                                        <div class="avatar-display"><i class="fa fa-user-alt"></i></div>
                                    </div>
                                </div>
                                <div class="rich-list-content">
                                    <h3 class="rich-list-title text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                                    <span class="rich-list-subtitle text-white"><?php echo htmlspecialchars($_SESSION['useremail']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (in_array($_SESSION['role'], [3,4,5])): ?>
                                <button type="button" class="btn btn-label-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#registerGuruModal">
                                    Register Guru
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer card-footer-bordered rounded-0">
                            <a href="../Starterkit/logout.php" class="btn btn-label-danger w-100">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End topbar -->

<!-- Modal Register Guru -->
<div class="modal fade" id="registerGuruModal" tabindex="-1" aria-labelledby="registerGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="./partials/register_guru.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerGuruModalLabel">Register Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan nama guru" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Guru</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email guru" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .logo-text { font-family: 'Poppins',sans-serif; font-size:15px; font-weight:700; letter-spacing:1px; }
    .navbar-header { min-height:48px !important; }
    @media (max-width: 600px) {
        .logo-text { font-size:12px !important;}
        .navbar-header img { height:26px !important; max-width:26px !important; }
        .navbar-header { padding-left: 7px !important; padding-right: 7px !important; }
    }
    @media (max-width: 400px) {
        .logo-text { font-size:11px !important; }
    }
</style>
