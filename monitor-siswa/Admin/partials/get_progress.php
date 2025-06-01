<?php
require_once '../Starterkit/partials/config.php';

// Query untuk menghitung progress siswa berdasarkan tanggal dan waktu sekarang
$sql = "
    SELECT 
        ck.siswa_id,
        s.nama_siswa,
        COUNT(CASE 
            WHEN ck.status = 'Tepat Waktu' 
                 AND (
                     (TIME(ck.waktu_checkpoint) BETWEEN TIME(k.jam_mulai) AND TIME(k.jam_selesai) AND k.jam_mulai <= k.jam_selesai)
                     OR 
                     ((TIME(ck.waktu_checkpoint) >= TIME(k.jam_mulai) OR TIME(ck.waktu_checkpoint) <= TIME(k.jam_selesai)) AND k.jam_mulai > k.jam_selesai)
                 )
            THEN 1 
        END) AS kegiatan_tepat_waktu,
        (COUNT(CASE 
            WHEN ck.status = 'Tepat Waktu' 
                 AND (
                     (TIME(ck.waktu_checkpoint) BETWEEN TIME(k.jam_mulai) AND TIME(k.jam_selesai) AND k.jam_mulai <= k.jam_selesai)
                     OR 
                     ((TIME(ck.waktu_checkpoint) >= TIME(k.jam_mulai) OR TIME(ck.waktu_checkpoint) <= TIME(k.jam_selesai)) AND k.jam_mulai > k.jam_selesai)
                 )
            THEN 1 
        END) / 7) * 100 AS progress
    FROM 
        checkpoint_kegiatan ck
    JOIN 
        siswa s ON ck.siswa_id = s.id
    JOIN 
        kegiatan k ON ck.kegiatan_id = k.id
    WHERE 
        DATE(ck.waktu_checkpoint) = CURDATE()
    GROUP BY 
        ck.siswa_id, s.nama_siswa
";

$result = mysqli_query($link, $sql);

if (!$result) {
    die('Query gagal: ' . mysqli_error($link));
}

return $result;
?>