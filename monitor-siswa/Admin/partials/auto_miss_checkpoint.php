<?php
require_once __DIR__ . '/../../Starterkit/partials/config.php';


date_default_timezone_set('Asia/Jakarta');
$tanggalHariIni = date('Y-m-d');
$waktuSekarang = time(); // Waktu dalam timestamp

// Ambil semua siswa yang terdaftar pada kegiatan
$sql = "
    SELECT msk.siswa_id, msk.kegiatan_id, k.jam_mulai, k.jam_selesai
    FROM mapping_siswa_kegiatan msk
    JOIN kegiatan k ON msk.kegiatan_id = k.id
";
$result = mysqli_query($link, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($link));
}

while ($row = mysqli_fetch_assoc($result)) {
    $siswa_id = $row['siswa_id'];
    $kegiatan_id = $row['kegiatan_id'];
    $jam_mulai = $row['jam_mulai'];
    $jam_selesai = $row['jam_selesai'];

    $start_time = strtotime("$tanggalHariIni $jam_mulai");
    $end_time = strtotime("$tanggalHariIni $jam_selesai");

    if ($end_time < $start_time) {
        $end_time = strtotime('+1 day', $end_time);
    }

    $deadline_checkpoint = $end_time + (10 * 60); // Tambahan 10 menit

    // Cek apakah waktu sekarang sudah lebih dari deadline
    if ($waktuSekarang > $deadline_checkpoint) {
        // Cek apakah sudah ada checkpoint
        $cek_sql = "SELECT 1 FROM checkpoint_kegiatan WHERE siswa_id = ? AND kegiatan_id = ? AND DATE(waktu_checkpoint) = ?";
        $cek_stmt = mysqli_prepare($link, $cek_sql);
        mysqli_stmt_bind_param($cek_stmt, 'iis', $siswa_id, $kegiatan_id, $tanggalHariIni);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) === 0) {
            // Insert "Tidak Checkpoint"
            $insert_sql = "INSERT INTO checkpoint_kegiatan (siswa_id, kegiatan_id, waktu_checkpoint, status) VALUES (?, ?, NOW(), 'Tidak Checkpoint')";
            $insert_stmt = mysqli_prepare($link, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, 'ii', $siswa_id, $kegiatan_id);
            if (mysqli_stmt_execute($insert_stmt)) {
                echo "Berhasil tambah checkpoint siswa ID $siswa_id untuk kegiatan ID $kegiatan_id<br>";
            } else {
                echo "Gagal insert: " . mysqli_error($link) . "<br>";
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($cek_stmt);
    }
}

echo "Auto check selesai.";
