<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guru_id = intval($_POST['guru_id']);
    $kelas_ids = $_POST['kelas_id'];

    // Hapus mapping lama
    $deleteQuery = "DELETE FROM mapping_guru_kelas WHERE guru_id = $guru_id";
    mysqli_query($link, $deleteQuery);

    // Tambahkan mapping baru
    foreach ($kelas_ids as $kelas_id) {
        $insertQuery = "INSERT INTO mapping_guru_kelas (guru_id, kelas_id) VALUES ($guru_id, $kelas_id)";
        mysqli_query($link, $insertQuery);
    }

    // Redirect kembali ke halaman list guru
    $_SESSION['success'] = 'Mapping kelas berhasil diperbarui.';
    header('Location: list_guru.php');
    exit;
}
?>