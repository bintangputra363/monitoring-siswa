<?php
session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if ($id !== '') {
        // Cek apakah mapping dengan ID tersebut ada
        $check = mysqli_query($link, "SELECT id FROM mapping_siswa_kegiatan WHERE id = '$id'");
        if (mysqli_num_rows($check) > 0) {
            // Hapus mapping
            $delete = mysqli_query($link, "DELETE FROM mapping_siswa_kegiatan WHERE id = '$id'");
            if ($delete) {
                $_SESSION['success'] = "Mapping berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus mapping.";
            }
        } else {
            $_SESSION['error'] = "Mapping tidak ditemukan.";
        }
    } else {
        $_SESSION['error'] = "ID mapping tidak ditemukan.";
    }

    header("Location: ../mapping-siswa.php");
    exit;
}
?>