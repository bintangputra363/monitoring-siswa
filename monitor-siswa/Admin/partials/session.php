<?php
/**
 * session.php
 * * File ini berfungsi sebagai "penjaga" untuk halaman-halaman yang memerlukan login.
 * 1. Memulai session.
 * 2. Memeriksa apakah pengguna sudah login. Jika belum, akan diarahkan ke halaman login.
 * 3. Memeriksa role pengguna dan memastikan mereka hanya bisa mengakses halaman yang sesuai dengan hak aksesnya.
 */

// Selalu mulai session di baris paling awal sebelum ada output apapun.
session_start();

// --- 1. Pengecekan Status Login ---
// Periksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
// Pengecualiannya adalah halaman login itu sendiri, agar tidak terjadi redirect loop.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
        // Gunakan path yang sesuai dari lokasi file Anda menuju halaman login.
        // Asumsi file ini ada di dalam folder /partials, maka ../ akan keluar ke folder Starterkit.
        header("location: auth-login.php");
        exit;
    }
    // Jika sudah di halaman login dan belum login, maka tidak perlu melakukan apa-apa.
    return;
}

// --- 2. Pengecekan Role Pengguna ---
// Pastikan variabel session 'role' ada sebelum melakukan pengecekan.
if (isset($_SESSION["role"])) {
    
    // Pengecualian untuk halaman logout:
    // Jika halaman yang sedang diakses adalah auth-logout.php, jangan lakukan redirect apapun.
    // Ini PENTING untuk mencegah infinite loop dan membiarkan proses logout berjalan.
    if (basename($_SERVER['PHP_SELF']) === 'auth-logout.php') {
        return; 
    }
    
    // Ambil role pengguna dari session untuk mempermudah penulisan.
    $userRole = $_SESSION["role"];

    // Kelompokkan semua role yang memiliki akses ke area Admin.
    $adminRoles = [1, 3, 4]; // 1: Guru, 3: Admin, 4: Kepala Sekolah

    if (in_array($userRole, $adminRoles)) { 
        // Jika role adalah salah satu dari adminRoles, mereka harus berada di area Admin.
        // Jika mereka mencoba mengakses halaman di luar folder /Admin/, arahkan mereka kembali.
        // strpos() digunakan untuk memeriksa apakah URL mengandung '/Admin/'.
        if (strpos($_SERVER['PHP_SELF'], '/Admin/') === false) {
            // Path ini mengasumsikan halaman admin ada di folder 'Admin' satu level di atas 'Starterkit'.
            // Sesuaikan jika perlu.
            header("location: ../Admin/index.php"); 
            exit;
        }
    } 
    // Penanganan untuk role Siswa.
    elseif ($userRole == 2) {
        // Siswa hanya boleh mengakses halaman 'kegiatan-siswa.php'.
        // Jika mereka mencoba mengakses halaman lain, arahkan mereka kembali.
        if (basename($_SERVER['PHP_SELF']) !== 'kegiatan-siswa.php') {
            header("location: kegiatan-siswa.php");
            exit;
        }
    } 
    // Penanganan untuk role yang tidak dikenali.
    else {
        // Jika role pengguna tidak ada di dalam daftar di atas (misal role 5, 6, atau data korup),
        // maka ini adalah kondisi tidak normal. Logout paksa pengguna untuk keamanan.
        header("location: auth-logout.php");
        exit;
    }
} else {
    // Jika pengguna login tapi session 'role' tidak ada (kasus aneh/data korup),
    // Logout paksa untuk keamanan.
    header("location: auth-logout.php");
    exit;
}
?>