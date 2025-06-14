<?php
require_once '../../Starterkit/partials/config.php';
session_start();

// Cek login
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Akses ditolak. Silakan login terlebih dahulu.']);
    exit;
}

// Ambil role dari sesi
$role = $_SESSION['role']; // 1 = guru

// Untuk guru, cek kepemilikan kelas
if ($role == 1) {
    if (!isset($_SESSION['kelas_ids'])) {
        $_SESSION['kelas_ids'] = [];
    }

    // Jika ada kelas_ids, validasi kelas_id
    if (!empty($_SESSION['kelas_ids'])) {
        if (!isset($_GET['kelas_id'])) {
            echo json_encode(['error' => 'Kelas ID tidak ditemukan.']);
            exit;
        }
        $kelas_id = intval($_GET['kelas_id']);
        if (!in_array($kelas_id, $_SESSION['kelas_ids'], true)) {
            echo json_encode(['error' => 'Akses ditolak. Kelas ini bukan milik Anda.']);
            exit;
        }
    }
}

// Untuk admin dan role lain, tidak perlu validasi kelas_ids

if (!isset($_GET['kelas_id'])) {
    echo json_encode(['error' => 'Kelas ID tidak ditemukan.']);
    exit;
}

$kelas_id = intval($_GET['kelas_id']);
$tanggal  = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query ambil siswa, nama guru, jumlah tepat waktu, dan total mapping kegiatan
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
        $tepat = (int)$row['kegiatan_tepat_waktu'];
        $total = (int)$row['total_kegiatan'];
        // Progress dinamis berdasarkan mapping masing-masing siswa
        $row['progress'] = ($total > 0) ? round(($tepat / $total) * 100, 2) : 0;
        $row['kegiatan_tepat_waktu'] = $tepat;
        $row['total_kegiatan'] = $total;
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Gagal mengambil data progress: ' . mysqli_error($link)]);
}
?>
