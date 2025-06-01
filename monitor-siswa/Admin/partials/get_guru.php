<?php
require_once '../../Starterkit/partials/config.php';

// Query untuk mengambil data guru
$sql = "
    SELECT 
        u.id AS guru_id,
        u.username AS nama_guru,
        GROUP_CONCAT(k.nama_kelas SEPARATOR ', ') AS kelas
    FROM 
        users u
    LEFT JOIN 
        mapping_guru_kelas mgk ON u.id = mgk.guru_id
    LEFT JOIN 
        kelas k ON mgk.kelas_id = k.id
    WHERE 
        u.role_user = 1
    GROUP BY 
        u.id
    ORDER BY 
        u.username ASC
";

$result = mysqli_query($link, $sql);

if (!$result) {
    die(json_encode(['error' => 'Query gagal: ' . mysqli_error($link)]));
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Kembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>