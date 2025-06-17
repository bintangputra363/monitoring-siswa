<?php
session_start();

// Redirect ke login jika belum login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Allow hanya di halaman login
    if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
        header("Location: auth-login.php");
        exit;
    }
}

// Role redirect mapping
$roleRedirect = [
    1 => '../Admin/index.php',              // Guru/Admin
    2 => '../Starterkit/kegiatan-siswa.php',// Siswa
    3 => '../Superadmin/dashboard.php',     // Superadmin
    4 => '../Kepsek/dashboard.php',         // Kepala Sekolah
    // Tambahkan sesuai kebutuhan
];

// Jika sudah login tapi rolenya tidak valid
if (!isset($_SESSION['role']) || !isset($roleRedirect[$_SESSION['role']])) {
    session_destroy();
    header("Location: auth-login.php");
    exit;
}

// Optional: blokir akses halaman tertentu (misal siswa akses admin)
$role = $_SESSION['role'];
if ($role == 2 && strpos($_SERVER['PHP_SELF'], '/Admin/') !== false) {
    // Siswa tidak boleh akses folder Admin
    header("Location: " . $roleRedirect[$role]);
    exit;
}
// Tambah pengecekan lain sesuai kebutuhan
?>
