<!-- Start topbar -->
<header id="page-topbar">
    <div class="navbar-header">

        <!-- Logo -->

        <!-- Start Navbar-Brand -->
        <div class="navbar-logo-box">
            <a href="index.php" class="logo logo-dark">
                <h1 class="logo-text">Monitor Siswa</h1>
            </a>
            
            <a href="index.php" class="logo logo-light">
                <h1 class="logo-text">Monitor Siswa</h1>
            </a>

            <button type="button" class="btn btn-sm top-icon sidebar-btn" id="sidebar-btn">
                <i class="mdi mdi-menu-open align-middle fs-19"></i>
            </button>
        </div>
        <!-- End navbar brand -->
<!-- Logo SMP dan Nama Sekolah -->
    <div class="d-flex align-items-center ms-5">
    <img src="assets/images/logoSMP.webp" alt="Logo SMP" style="height: 50px; margin-right: 10px; margin: left 20px;">
    <div>
        <span style="font-weight: bold; color: #4CAF50; font-size: 17px; line-height: 1.2;">
            SMP Negeri 4 Kota Tangerang
        </span>
    </div>
</div>

        <!-- Start menu -->
        <div class="d-flex justify-content-between menu-sm px-3 ms-auto">
            <div class="d-flex align-items-center gap-2">
            
            </div>

            <div class="d-flex align-items-center gap-2">
                <!--Start App Search-->
                <!--End App Search-->

                <!-- Start Notification -->

                <!-- End Notification -->

                <!-- Start Activities -->
                <!-- End Activities -->

                <!-- Start Profile -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm top-icon p-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded avatar-2xs p-0" src="assets/images/users/avatar-6.png" alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-wide dropdown-menu-end dropdown-menu-animated overflow-hidden py-0">
                        <div class="card border-0">
                            <div class="card-header bg-primary rounded-0">
                                <div class="rich-list-item w-100 p-0">
                                    <div class="rich-list-prepend">
                                        <div class="avatar avatar-label-light avatar-circle">
                                            <div class="avatar-display"><i class="fa fa-user-alt"></i></div>
                                        </div>
                                    </div>
                                    <div class="rich-list-content">
                                        <h3 class="rich-list-title text-white"><?php echo $_SESSION['username']; ?></h3>
                                        <span class="rich-list-subtitle text-white"><?php echo $_SESSION['useremail']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-label-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#registerGuruModal">
                                    Register Guru
                                </button>
                            </div>
                            <div class="card-footer card-footer-bordered rounded-0">
                                <a href="../Starterkit/logout.php" class="btn btn-label-danger w-100">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Profile -->
            </div>
        </div>
        <!-- End menu -->
    </div>
</header>
<!-- End topbar -->

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
    .logo-text {
        font-family: 'Poppins', sans-serif; /* Font modern */
        font-size: 24px; /* Ukuran default */
        font-weight: bold;
        color: #4CAF50; /* Warna hijau */
        text-transform: uppercase; /* Huruf kapital semua */
        letter-spacing: 2px; /* Jarak antar huruf */
        margin: 0; /* Hilangkan margin default */
        transition: font-size 0.3s ease, transform 0.3s ease; /* Efek transisi */
    }

    .logo-text.small {
        font-size: 16px; /* Ukuran lebih kecil */
        transform: scale(0.9); /* Sedikit mengecil */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarBtn = document.getElementById('sidebar-btn');
        const logoText = document.querySelectorAll('.logo-text');

        sidebarBtn.addEventListener('click', function () {
            logoText.forEach(logo => {
                logo.classList.toggle('small'); // Tambahkan atau hapus kelas 'small'
            });
        });
    });
</script>