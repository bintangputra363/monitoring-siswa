<?php
// Initialize the session
// session_start();

// Check if the user is logged in, if not then redirect him to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//      header("location: auth-login.php");
//     exit;
//  }


// Initialize the session
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika belum login, arahkan ke halaman login
    if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
        header("location: ../Starterkit/auth-login.php"); // Sesuaikan jalur relatif
        exit;
    }
}

// Periksa role pengguna
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 2) {
        // Role 2: Pengguna diarahkan ke kegiatan siswa
        if (basename($_SERVER['PHP_SELF']) !== 'kegiatan-siswa.php') {
            header("location: ../Starterkit/kegiatan-siswa.php"); // Sesuaikan jalur relatif
            exit;
        }
    } elseif ($_SESSION["role"] == 1) {
        // Role 1: Pengguna diarahkan ke halaman admin
        if (basename($_SERVER['PHP_SELF']) !== 'index.php' && strpos($_SERVER['PHP_SELF'], '/Admin/') === false) {
            header("location: ../index.php"); // Sesuaikan jalur relatif
            exit;
        }
    } else {
        // Role tidak dikenal, logout pengguna
        header("location: ../../Starterkit/auth-logout.php"); // Sesuaikan jalur relatif
        exit;
    }
}
?>