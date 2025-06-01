<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\add_kegiatan.php

// Mulai session
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../Starterkit/auth-login.php");
    exit;
}

// Include file koneksi database
require_once '../../Starterkit/partials/config.php';

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_kegiatan = trim($_POST['nama_kegiatan']);
    $deskripsi = trim($_POST['deskripsi']);
    $jam_mulai = trim($_POST['jam_mulai']);
    $jam_selesai = trim($_POST['jam_selesai']);

    // Validasi data
    if (empty($nama_kegiatan) || empty($deskripsi)  || empty($jam_mulai) || empty($jam_selesai)) {
        $_SESSION['error'] = "Semua field harus diisi.";
        header("location: ../kegiatan.php");
        exit;
    }

    // Periksa apakah nama kegiatan sudah ada
    $check_sql = "SELECT id FROM kegiatan WHERE nama_kegiatan = ?";
    if ($check_stmt = mysqli_prepare($link, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "s", $param_nama_kegiatan);
        $param_nama_kegiatan = $nama_kegiatan;

        // Eksekusi statement
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Nama kegiatan sudah ada
            $_SESSION['error'] = "Nama kegiatan sudah ada. Silakan gunakan nama lain.";
            header("location: ../kegiatan.php");
            exit;
        }

        // Tutup statement
        mysqli_stmt_close($check_stmt);
    }

    // Generate GUID
    function generateGUID() {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        } else {
            return sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
    }

    $id = generateGUID();

    // Query untuk menyimpan data ke tabel kegiatan
    $sql = "INSERT INTO kegiatan (id, nama_kegiatan, deskripsi, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameter ke statement
        mysqli_stmt_bind_param($stmt, "sssss", $param_id, $param_nama_kegiatan, $param_deskripsi, $param_jam_mulai, $param_jam_selesai);

        // Set parameter
        $param_id = $id;
        $param_nama_kegiatan = $nama_kegiatan;
        $param_deskripsi = $deskripsi;
        $param_jam_mulai = $jam_mulai;
        $param_jam_selesai = $jam_selesai;

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect ke halaman kegiatan dengan pesan sukses
            $_SESSION['success'] = "Kegiatan berhasil ditambahkan.";
            header("location: ../kegiatan.php");
        } else {
            $_SESSION['error'] = "Terjadi kesalahan. Silakan coba lagi.";
            header("location: ../kegiatan.php");
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Terjadi kesalahan pada query.";
        header("location: ../kegiatan.php");
    }

    // Tutup koneksi
    mysqli_close($link);
} else {
    // Jika file diakses langsung, redirect ke halaman kegiatan
    header("location: ../kegiatan.php");
    exit;
}