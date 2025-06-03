<?php
require_once '../../Starterkit/partials/config.php';
session_start();

// Cek apakah user login dan role-nya guru
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1 || !isset($_SESSION['kelas_ids'])) {
    echo json_encode(['error' => 'Akses ditolak.']);
    exit;
}

if (isset($_GET['kelas_id'])) {
    $kelas_id = intval($_GET['kelas_id']);
    $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

    // Cek apakah kelas ini dimiliki oleh guru
    if (!in_array($kelas_id, $_SESSION['kelas_ids'])) {
        echo json_encode(['error' => 'Kelas ini bukan milik Anda.']);
        exit;
    }

    // SQL untuk ambil data siswa dan progres
    $sql = "
        SELECT 
            s.id AS siswa_id,
            s.nama_siswa,
            GROUP_CONCAT(DISTINCT u.username SEPARATOR ', ') AS nama_guru,
            COUNT(DISTINCT CASE 
                WHEN DATE(ck.waktu_checkpoint) = '$tanggal' AND ck.status = 'Tepat Waktu' 
                THEN ck.kegiatan_id 
            END) AS kegiatan_tepat_waktu,
            (
                SELECT COUNT(*) 
                FROM mapping_siswa_kegiatan 
                WHERE siswa_id = s.id
            ) AS total_kegiatan
        FROM 
            siswa s
        LEFT JOIN 
            checkpoint_kegiatan ck ON ck.siswa_id = s.id
        LEFT JOIN 
            mapping_guru_kelas mgk ON s.kelas_id = mgk.kelas_id
        LEFT JOIN 
            users u ON mgk.guru_id = u.id
        WHERE 
            s.kelas_id = $kelas_id
        GROUP BY 
            s.id, s.nama_siswa
    ";

    $result = mysqli_query($link, $sql);

    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $total = (int)$row['total_kegiatan'];
            $tepat = (int)$row['kegiatan_tepat_waktu'];
            $row['progress'] = $total > 0 ? ($tepat / $total) * 100 : 0;
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Gagal mengambil data progress: ' . mysqli_error($link)]);
    }
} else {
    echo json_encode(['error' => 'Kelas ID tidak ditemukan.']);
}
