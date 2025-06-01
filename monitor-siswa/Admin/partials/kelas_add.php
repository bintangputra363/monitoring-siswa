<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kelas = mysqli_real_escape_string($link, $_POST['nama_kelas']);

    // Query untuk menambahkan kelas
    $sql = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";

    if (mysqli_query($link, $sql)) {
        $_SESSION['success'] = 'Kelas berhasil ditambahkan.';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan kelas: ' . mysqli_error($link);
    }

    // Redirect kembali ke halaman kelas
    header('Location: ../kelas.php');
    exit;
}
?>