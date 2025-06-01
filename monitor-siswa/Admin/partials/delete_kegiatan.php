<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\partials\delete_kegiatan.php

session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Query untuk menghapus data
    $sql = "DELETE FROM kegiatan WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id);

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Kegiatan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menghapus data.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Terjadi kesalahan pada query.";
    }

    mysqli_close($link);
    header("location: ../kegiatan.php");
    exit;
}
?>