<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama_kelas = mysqli_real_escape_string($link, $_POST['nama_kelas']);

    // Query untuk mengupdate kelas
    $sql = "UPDATE kelas SET nama_kelas = '$nama_kelas' WHERE id = $id";

    if (mysqli_query($link, $sql)) {
        $_SESSION['success'] = 'Kelas berhasil diperbarui.';
    } else {
        $_SESSION['error'] = 'Gagal memperbarui kelas: ' . mysqli_error($link);
    }

    // Redirect kembali ke halaman kelas
    header('Location: ../kelas.php');
    exit;
}
?>