<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guru_id = intval($_POST['guru_id']);

    // Hapus semua mapping untuk guru ini
    $deleteQuery = "DELETE FROM mapping_guru_kelas WHERE guru_id = $guru_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION['success'] = 'Mapping guru berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus mapping guru: ' . mysqli_error($link);
    }

    // Redirect kembali ke halaman list guru
    header('Location: ../list_guru.php');
    exit;
}
?>