<?php
require_once(__DIR__ . '/../Starterkit/partials/config.php');

// Ambil parameter GET
$kelas_id = isset($_GET['kelas_id']) ? intval($_GET['kelas_id']) : 0;
$tgl_mulai = isset($_GET['start']) ? $_GET['start'] : date('Y-m-01');
$tgl_selesai = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

if (!$kelas_id) {
    die("Kelas tidak ditemukan.");
}

// Ambil nama kelas
$q_kelas = mysqli_query($link, "SELECT nama_kelas FROM kelas WHERE id = $kelas_id");
$row_kelas = mysqli_fetch_assoc($q_kelas);
$nama_kelas = $row_kelas['nama_kelas'] ?? '-';

// Ambil semua siswa di kelas
$siswa_sql = "SELECT id, nama_siswa FROM siswa WHERE kelas_id = $kelas_id ORDER BY nama_siswa ASC";
$siswa_res = mysqli_query($link, $siswa_sql);

$siswa_data = [];
while ($s = mysqli_fetch_assoc($siswa_res)) {
    $siswa_data[$s['id']] = [
        'nama' => $s['nama_siswa'],
        'rekap' => [],
    ];
}

// Ambil semua kegiatan
$kegiatan_sql = "
    SELECT DISTINCT k.id, k.nama_kegiatan
    FROM mapping_siswa_kegiatan msk
    JOIN kegiatan k ON msk.kegiatan_id = k.id
    JOIN siswa s ON msk.siswa_id = s.id
    WHERE s.kelas_id = $kelas_id
    ORDER BY k.nama_kegiatan ASC
";
$kegiatan_res = mysqli_query($link, $kegiatan_sql);
$daftar_kegiatan = [];
while ($k = mysqli_fetch_assoc($kegiatan_res)) {
    $daftar_kegiatan[$k['id']] = $k['nama_kegiatan'];
}

// Ambil checkpoint
$checkpoint_sql = "
    SELECT ck.siswa_id, ck.kegiatan_id, DATE(ck.waktu_checkpoint) AS tgl, ck.status, ck.status_verifikasi
    FROM checkpoint_kegiatan ck
    JOIN siswa s ON ck.siswa_id = s.id
    WHERE s.kelas_id = $kelas_id
      AND DATE(ck.waktu_checkpoint) BETWEEN '$tgl_mulai' AND '$tgl_selesai'
";
$checkpoint_res = mysqli_query($link, $checkpoint_sql);
while ($c = mysqli_fetch_assoc($checkpoint_res)) {
    $siswa_data[$c['siswa_id']]['rekap'][$c['tgl']][$c['kegiatan_id']] = [
        'status' => $c['status'],
        'verif'  => $c['status_verifikasi']
    ];
}

// Buat tanggal-tanggal range
$periode = [];
$start = new DateTime($tgl_mulai);
$end = new DateTime($tgl_selesai);
for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
    $periode[] = $d->format('Y-m-d');
}

// Output header untuk Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Kegiatan_Kelas_$nama_kelas.xls");

// Mulai output HTML
echo '<html><head><meta charset="UTF-8"></head><body>';
echo '<h2 style="text-align:center;">Rekap Kegiatan Siswa</h2>';
echo '<p><strong>Kelas:</strong> ' . htmlspecialchars($nama_kelas) . '<br>
<strong>Periode:</strong> ' . htmlspecialchars($tgl_mulai) . ' s/d ' . htmlspecialchars($tgl_selesai) . '</p>';

echo '<table border="1" cellpadding="6" cellspacing="0" width="100%" style="border-collapse:collapse; font-size:10pt;">';
echo '<thead>
<tr style="background:#efefef;">
    <th>No</th>
    <th>Nama Siswa</th>';

foreach ($daftar_kegiatan as $id_keg => $nama_keg) {
    echo '<th>' . htmlspecialchars($nama_keg) . '</th>';
}
echo '<th>Total Kegiatan</th><th>Sudah Checkpoint</th></tr></thead><tbody>';

$no = 1;
foreach ($siswa_data as $id_siswa => $siswa) {
    $total_kegiatan = count($daftar_kegiatan) * count($periode);
    $sudah = 0;
    echo '<tr>';
    echo '<td>' . $no++ . '</td>';
    echo '<td>' . htmlspecialchars($siswa['nama']) . '</td>';

    foreach ($daftar_kegiatan as $id_keg => $nama_keg) {
        $count_ck = 0;
        foreach ($periode as $tgl) {
            if (
                isset($siswa['rekap'][$tgl][$id_keg]) &&
                $siswa['rekap'][$tgl][$id_keg]['verif'] == 'disetujui'
            ) {
                $count_ck++;
            }
        }
        $sudah += $count_ck;
        echo '<td align="center">' . $count_ck . '</td>';
    }
    echo '<td align="center">' . $total_kegiatan . '</td>';
    echo '<td align="center">' . $sudah . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</body></html>';
?>