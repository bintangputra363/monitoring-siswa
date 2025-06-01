<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Admin\partials\add_mapping.php

session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $siswa_id = intval($_POST['siswa_id']);
    $kegiatan_ids = $_POST['kegiatan_id']; // Array kegiatan_id (GUID)

    // Validasi input
    if (!empty($siswa_id) && !empty($kegiatan_ids)) {
        foreach ($kegiatan_ids as $kegiatan_id) {
            $kegiatan_id = mysqli_real_escape_string($link, $kegiatan_id); // Pastikan GUID aman

            // Periksa apakah mapping sudah ada
            $check_sql = "SELECT * FROM mapping_siswa_kegiatan WHERE siswa_id = ? AND kegiatan_id = ?";
            if ($check_stmt = mysqli_prepare($link, $check_sql)) {
                mysqli_stmt_bind_param($check_stmt, "is", $siswa_id, $kegiatan_id); // "is" untuk INT dan STRING
                mysqli_stmt_execute($check_stmt);
                $result = mysqli_stmt_get_result($check_stmt);

                if (mysqli_num_rows($result) > 0) {
                    // Jika mapping sudah ada
                    $_SESSION['error'] = "Siswa ini sudah terdaftar pada salah satu kegiatan yang dipilih.";
                } else {
                    // Simpan mapping ke tabel mapping_siswa_kegiatan
                    $sql = "INSERT INTO mapping_siswa_kegiatan (siswa_id, kegiatan_id) VALUES (?, ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "is", $siswa_id, $kegiatan_id); // "is" untuk INT dan STRING
                        if (mysqli_stmt_execute($stmt)) {
                            $_SESSION['success'] = "Mapping siswa ke kegiatan berhasil ditambahkan.";
                        } else {
                            $_SESSION['error'] = "Gagal menambahkan mapping.";
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
                mysqli_stmt_close($check_stmt);
            }
        }
    } else {
        $_SESSION['error'] = "Siswa dan kegiatan harus dipilih.";
    }

    mysqli_close($link);
    header("location: ../mapping-siswa.php");
    exit;
}
?>