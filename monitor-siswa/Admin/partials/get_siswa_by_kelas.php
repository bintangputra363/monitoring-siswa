<?php
require_once '../../Starterkit/partials/config.php';

if (isset($_GET['kelas_id'])) {
    $kelas_id = mysqli_real_escape_string($link, $_GET['kelas_id']);
    $result = mysqli_query($link, "SELECT id, nama_siswa FROM siswa WHERE kelas_id = '$kelas_id' ORDER BY nama_siswa");

    $siswa_list = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $siswa_list[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($siswa_list);
}
?>
