<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\partials\add_siswa.php

session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_siswa = $_POST['nama_siswa'];
     $kelas_id = intval($_POST['kelas_id']);
    $username = $_POST['username'];
    $useremail = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role_user = 2; // Misalnya, 2 untuk siswa
    $token = bin2hex(random_bytes(50)); // Generate token unik

    // Simpan ke tabel users
    $user_sql = "INSERT INTO users (useremail, username, password, role_user, token) VALUES (?, ?, ?, ?, ?)";
    if ($user_stmt = mysqli_prepare($link, $user_sql)) {
        mysqli_stmt_bind_param($user_stmt, "sssis", $useremail, $username, $password, $role_user, $token);
        if (mysqli_stmt_execute($user_stmt)) {
            // Ambil ID user yang baru saja ditambahkan
            $user_id = mysqli_insert_id($link);

            // Simpan ke tabel siswa
            $siswa_sql = "INSERT INTO siswa (nama_siswa, kelas_id, user_id) VALUES (?, ?, ?)";
            if ($siswa_stmt = mysqli_prepare($link, $siswa_sql)) {
                mysqli_stmt_bind_param($siswa_stmt, "ssi", $nama_siswa, $kelas_id, $user_id);
                if (mysqli_stmt_execute($siswa_stmt)) {
                    $_SESSION['success'] = "Siswa berhasil ditambahkan.";
                } else {
                    $_SESSION['error'] = "Gagal menyimpan data siswa.";
                }
                mysqli_stmt_close($siswa_stmt);
            }
        } else {
            $_SESSION['error'] = "Gagal menyimpan data pengguna.";
        }
        mysqli_stmt_close($user_stmt);
    }

    mysqli_close($link);
    header("location: ../daftar_siswa.php");
    exit;
}
?>