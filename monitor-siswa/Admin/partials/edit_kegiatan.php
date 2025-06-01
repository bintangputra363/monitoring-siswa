<?php
session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Validasi: Periksa apakah nama kegiatan sudah digunakan oleh ID lain
    $check_sql = "SELECT id FROM kegiatan WHERE nama_kegiatan = ? AND id != ?";
    if ($check_stmt = mysqli_prepare($link, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "ss", $nama_kegiatan, $id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Nama kegiatan sudah digunakan oleh ID lain
            $_SESSION['error'] = "Nama kegiatan sudah digunakan. Silakan gunakan nama lain.";
            header("location: ../kegiatan.php");
            exit;
        }

        mysqli_stmt_close($check_stmt);
    }

    // Lanjutkan proses update jika validasi lolos
    $sql = "UPDATE kegiatan SET nama_kegiatan = ?, deskripsi = ?, jam_mulai = ?, jam_selesai = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $nama_kegiatan, $deskripsi, $jam_mulai, $jam_selesai, $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Kegiatan berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat memperbarui data.";
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
    header("location: ../kegiatan.php");
    exit;
}
?>