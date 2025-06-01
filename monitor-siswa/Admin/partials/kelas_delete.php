<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Query untuk menghapus kelas
    $sql = "DELETE FROM kelas WHERE id = $id";

    if (mysqli_query($link, $sql)) {
        $_SESSION['success'] = 'Kelas berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus kelas: ' . mysqli_error($link);
    }

    // Redirect kembali ke halaman kelas
    header('Location: ../kelas.php');
    exit;
}
?>