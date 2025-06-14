<?php
require_once '../../Starterkit/partials/config.php';
require_once '../Starterkit/partials/config.php';

// Ambil parameter
$kelas_id = isset($_GET['kelas_id']) ? intval($_GET['kelas_id']) : 0;
$start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d');
$end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_kelas_{$kelas_id}_{$start}_sd_{$end}.xls");

// Query siswa di kelas ini
$sql_siswa = "SELECT id, nama_siswa FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama_siswa";
$result_siswa = mysqli_query($link, $sql_siswa);

echo "<table border='1'>";
echo "<tr>
        <th>Nama Siswa</th>
        <th>Nama Kegiatan</th>
        <th>Tanggal</th>
        <th>Jam Mulai</th>
        <th>Jam Selesai</th>
        <th>Waktu Checkpoint</th>
        <th>Status</th>
        <th>Status Verifikasi</th>
        <th>Alasan Ditolak</th>
    </tr>";

while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
    $siswa_id = $row_siswa['id'];
    $nama_siswa = $row_siswa['nama_siswa'];

    // Ambil semua mapping kegiatan
    $sql_kegiatan = "
        SELECT k.id, k.nama_kegiatan, k.jam_mulai, k.jam_selesai
        FROM mapping_siswa_kegiatan msk
        JOIN kegiatan k ON msk.kegiatan_id = k.id
        WHERE msk.siswa_id = $siswa_id
        ORDER BY k.jam_mulai
    ";
    $result_kegiatan = mysqli_query($link, $sql_kegiatan);

    while ($row_keg = mysqli_fetch_assoc($result_kegiatan)) {
        $kegiatan_id = $row_keg['id'];
        $nama_kegiatan = $row_keg['nama_kegiatan'];
        $jam_mulai = $row_keg['jam_mulai'];
        $jam_selesai = $row_keg['jam_selesai'];

        // Untuk setiap tanggal dalam range
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            (new DateTime($end))->modify('+1 day')
        );
        foreach ($period as $dt) {
            $tanggal = $dt->format('Y-m-d');

            // Cek checkpoint
            $sql_ck = "
                SELECT waktu_checkpoint, status, status_verifikasi, alasan_tolak 
                FROM checkpoint_kegiatan 
                WHERE siswa_id = $siswa_id AND kegiatan_id = '$kegiatan_id' AND DATE(waktu_checkpoint) = '$tanggal'
                LIMIT 1
            ";
            $res_ck = mysqli_query($link, $sql_ck);
            if ($ck = mysqli_fetch_assoc($res_ck)) {
                $waktu_checkpoint = $ck['waktu_checkpoint'] ?: '-';
                $status = $ck['status'] ?: '-';
                $status_verifikasi = $ck['status_verifikasi'] ?: '-';
                $alasan_tolak = $ck['alasan_tolak'] ?: '';
            } else {
                $waktu_checkpoint = '-';
                $status = 'Belum Checkpoint';
                $status_verifikasi = '-';
                $alasan_tolak = '';
            }

            echo "<tr>
                <td>$nama_siswa</td>
                <td>$nama_kegiatan</td>
                <td>$tanggal</td>
                <td>$jam_mulai</td>
                <td>$jam_selesai</td>
                <td>$waktu_checkpoint</td>
                <td>$status</td>
                <td>$status_verifikasi</td>
                <td>$alasan_tolak</td>
            </tr>";
        }
    }
}
echo "</table>";
exit;
?>
