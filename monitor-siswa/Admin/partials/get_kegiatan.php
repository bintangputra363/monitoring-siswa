<?php
require_once '../../Starterkit/partials/config.php';

if (isset($_GET['siswa_id'])) {
    $siswa_id = intval($_GET['siswa_id']);
    $filter_date = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

    $sql = "
        SELECT 
            k.nama_kegiatan,
            k.jam_mulai,
            k.jam_selesai,
            ck.waktu_checkpoint,
            ck.status,
            ck.status_verifikasi,
            ck.alasan_tolak,
            ck.id AS checkpoint_id
        FROM 
            mapping_siswa_kegiatan msk
        JOIN 
            kegiatan k ON msk.kegiatan_id = k.id
        LEFT JOIN 
            checkpoint_kegiatan ck ON ck.kegiatan_id = k.id 
            AND ck.siswa_id = msk.siswa_id 
            AND DATE(ck.waktu_checkpoint) = '$filter_date'
        WHERE 
            msk.siswa_id = $siswa_id
        ORDER BY 
            k.jam_mulai
    ";

    $result = mysqli_query($link, $sql);

    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Cek sudah checkpoint (jika waktu_checkpoint ada isinya dan bukan NULL/kosong)
            $sudah_checkpoint = !empty($row['waktu_checkpoint']);

            $row['waktu_checkpoint'] = $row['waktu_checkpoint'] ?? '-';

            if (!$sudah_checkpoint) {
                $row['status'] = 'Belum Checkpoint';
                $row['status_verifikasi'] = '-';
                $row['alasan_tolak'] = '';
                $row['checkpoint_id'] = '';
            } else {
                $row['status'] = $row['status'] ?? '-';
                if ($row['status_verifikasi'] === null) {
                    $row['status_verifikasi'] = 'pending';
                }
                $row['alasan_tolak'] = $row['alasan_tolak'] ?? '';
                $row['checkpoint_id'] = $row['checkpoint_id'] ?? '';
            }
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Gagal mengambil data kegiatan: ' . mysqli_error($link)]);
    }
} else {
    echo json_encode(['error' => 'ID siswa tidak ditemukan.']);
}
?>
