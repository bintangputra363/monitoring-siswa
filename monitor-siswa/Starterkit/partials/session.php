<?php
// Initialize the session
// session_start();

// // Periksa apakah pengguna sudah login
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     // Jika belum login, arahkan ke halaman login
//     if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
//         header("location: auth-login.php");
//         exit;
//     }
// }

// // Periksa role pengguna
// if (isset($_SESSION["role"])) {
//     if ($_SESSION["role"] == 2) {
//         // Role 2: Pengguna diarahkan ke kegiatan siswa
//         if (basename($_SERVER['PHP_SELF']) !== 'kegiatan-siswa.php') {
//             header("location: kegiatan-siswa.php");
//             exit;
//         }
//     } elseif ($_SESSION["role"] == 1) {
//         // Role 1: Pengguna diarahkan ke halaman admin
//         if (basename($_SERVER['PHP_SELF']) !== 'index.php' && strpos($_SERVER['PHP_SELF'], '/Admin/') === false) {
//             header("location: ../Admin/index.php");
//             exit;
//         }
//     } else {
//         // Role tidak dikenal, logout pengguna
//         header("location: auth-logout.php");
//         exit;
//     }
// }

// Mulai session
// session_start();

// // Jika belum login, arahkan ke halaman login
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
//         header("Location: auth-login.php");
//         exit;
//     }
// }

// // Role: 2 = Siswa
// if (isset($_SESSION["role"]) && $_SESSION["role"] == 2) {
//     // Halaman yang boleh diakses siswa
//     $allowed_siswa_pages = [
//         'kegiatan-siswa.php',
//         'list-kegiatan-siswa.php',
//         'index.php'
//     ];

//     $current_page = basename($_SERVER['PHP_SELF']);
//     if (!in_array($current_page, $allowed_siswa_pages)) {
//         header("Location: kegiatan-siswa.php");
//         exit;
//     }
// }

// // Role: 1 = Admin
// elseif (isset($_SESSION["role"]) && $_SESSION["role"] == 1) {
//     $current_page = basename($_SERVER['PHP_SELF']);
//     if ($current_page !== 'index.php' && strpos($_SERVER['PHP_SELF'], '/Admin/') === false) {
//         header("Location: ../Admin/index.php");
//         exit;
//     }
// }

// // Role tidak dikenali
// elseif (!isset($_SESSION["role"])) {
//     header("Location: auth-logout.php");
//     exit;
// }


// session_start();

// $current_file = basename($_SERVER['PHP_SELF']);

// // Halaman yang tidak perlu cek login
// $public_pages = ['auth-login.php', 'auth-logout.php'];

// if (in_array($current_file, $public_pages)) {
//     // Jangan lakukan apapun, biarkan user akses login/logout
//     return;
// }

// // Jika belum login, arahkan ke login
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("Location: auth-login.php");
//     exit;
// }

// // Jika role siswa (2)
// if (isset($_SESSION["role"]) && $_SESSION["role"] == 2) {
//     $allowed_siswa_pages = [
//         'kegiatan-siswa.php',
//         'list-kegiatan-siswa.php',
//         'index.php'
//     ];
//     if (!in_array($current_file, $allowed_siswa_pages)) {
//         header("Location: kegiatan-siswa.php");
//         exit;
//     }
// }

// // Jika role admin (1)
// elseif (isset($_SESSION["role"]) && $_SESSION["role"] == 1) {
//     if ($current_file !== 'index.php' && strpos($_SERVER['PHP_SELF'], '/Admin/') === false) {
//         header("Location: ../Admin/index.php");
//         exit;
//     }
// }

// // Role tidak dikenal
// elseif (!isset($_SESSION["role"])) {
//     header("Location: auth-logout.php");
//     exit;
// }

// Mulai sesi
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Kecuali sedang di halaman login
    if (basename($_SERVER['PHP_SELF']) !== 'auth-login.php') {
        header("Location: auth-login.php");
        exit();
    }
}

// Role-based Access Control
if (isset($_SESSION["role"])) {
    $role = $_SESSION["role"];
    $currentPath = $_SERVER['PHP_SELF'];

    if ($role == 2) {
        // Role 2 hanya boleh akses halaman di folder Starterkit
        if (strpos($currentPath, '/Starterkit/') === false) {
            header("Location: Starterkit/kegiatan-siswa.php"); // pastikan path ini benar
            exit();
        }
    } elseif ($role == 1) {
        // Role 1 hanya boleh akses halaman di folder Admin
        if (strpos($currentPath, '/Admin/') === false) {
            header("Location: ../Admin/index.php"); // atau sesuaikan dengan struktur foldermu
            exit();
        }
    } else {
        // Role tidak dikenali, arahkan ke logout
        header("Location: auth-logout.php");
        exit();
    }
}

?>


