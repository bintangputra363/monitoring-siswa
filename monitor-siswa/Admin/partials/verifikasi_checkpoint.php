<?php
require_once '../../Starterkit/partials/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $aksi = $_POST['aksi'];

    if ($aksi == 'setujui') {
        $sql = "UPDATE checkpoint_kegiatan SET status_verifikasi='disetujui' WHERE id=$id";
        mysqli_query($link, $sql);
        $_SESSION['success'] = "Checkpoint disetujui.";
    } elseif ($aksi == 'tolak') {
        $alasan = mysqli_real_escape_string($link, $_POST['alasan_tolak']);
        $sql = "UPDATE checkpoint_kegiatan SET status_verifikasi='ditolak', alasan_tolak='$alasan' WHERE id=$id";
        mysqli_query($link, $sql);
        $_SESSION['success'] = "Checkpoint ditolak.";
    }
}
header("Location: ../index.php"); // ganti sesuai nama file dashboard-mu
exit;
?>
