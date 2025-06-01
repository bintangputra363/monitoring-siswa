<!-- ========== Left Sidebar Start ========== -->
<div class="sidebar-left">
    <div data-simplebar class="h-100">

        <!--- Sidebar-menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
          <ul class="left-menu list-unstyled" id="side-menu">
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'list-kegiatan-siswa.php' ? 'mm-active' : '' ?>">
        <a href="list-kegiatan-siswa.php" class="<?= basename($_SERVER['PHP_SELF']) == 'list-kegiatan-siswa.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i>
            <span>List Kegiatan</span>
        </a>
    </li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'kegiatan-siswa.php' ? 'mm-active' : '' ?>">
        <a href="kegiatan-siswa.php" class="<?= basename($_SERVER['PHP_SELF']) == 'kegiatan-siswa.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i>
            <span>Kegiatan</span>
        </a>
    </li>
    <?php if ($_SESSION['role'] == 2): ?>
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'progress-siswa.php' ? 'mm-active' : '' ?>">
            <a href="progress-siswa.php" class="<?= basename($_SERVER['PHP_SELF']) == 'progress-siswa.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i>
                <span>Progress Saya</span>
            </a>
        </li>
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'laporan-siswa.php' ? 'mm-active' : '' ?>">
            <a href="laporan-siswa.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan-siswa.php' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan Saya</span>
            </a>
        </li>
    <?php endif; ?>
</ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
