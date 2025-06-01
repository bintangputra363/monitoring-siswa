<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\partials\edit_siswa.php

session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $nama_siswa = $_POST['nama_siswa'];
        $kelas_id = intval($_POST['kelas_id']);
    $kelas = $_POST['kelas'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update tabel users
    if (!empty($password)) {
        // Jika password diisi, hash password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_sql = "UPDATE users SET username = ?, useremail = ?, password = ? WHERE id = ?";
        if ($user_stmt = mysqli_prepare($link, $user_sql)) {
            mysqli_stmt_bind_param($user_stmt, "sssi", $username, $email, $hashed_password, $user_id);
            mysqli_stmt_execute($user_stmt);
            mysqli_stmt_close($user_stmt);
        }
    } else {
        // Jika password tidak diisi, hanya update username dan email
        $user_sql = "UPDATE users SET username = ?, useremail = ? WHERE id = ?";
        if ($user_stmt = mysqli_prepare($link, $user_sql)) {
            mysqli_stmt_bind_param($user_stmt, "ssi", $username, $email, $user_id);
            mysqli_stmt_execute($user_stmt);
            mysqli_stmt_close($user_stmt);
        }
    }

    // Update tabel siswa
    $siswa_sql = "UPDATE siswa SET nama_siswa = ?, kelas_id = ? WHERE user_id = ?";
    if ($siswa_stmt = mysqli_prepare($link, $siswa_sql)) {
        mysqli_stmt_bind_param($siswa_stmt, "ssi", $nama_siswa, $kelas_id, $user_id);
        mysqli_stmt_execute($siswa_stmt);
        mysqli_stmt_close($siswa_stmt);
    }

    $_SESSION['success'] = "Data siswa berhasil diperbarui.";
    header("location: ../daftar_siswa.php");
    exit;
}
?>