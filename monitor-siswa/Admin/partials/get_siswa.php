<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\partials\get_siswa.php

require_once '../../Starterkit/partials/config.php';

$kelas_id = intval($_GET['kelas_id']);
$sql = "SELECT siswa.nama_siswa, kelas.nama_kelas,siswa.kelas_id, users.username, users.useremail, siswa.user_id
        FROM siswa 
        JOIN users ON siswa.user_id = users.id 
        LEFT JOIN 
        kelas  ON siswa.kelas_id = kelas.id
    
        WHERE siswa.kelas_id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $kelas_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

echo json_encode($data);
?>