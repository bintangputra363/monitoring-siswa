<?php
// filepath: c:\xampp\htdocs\monitor-siswa\Starterkit\partials\checkpoint.php

session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($_SESSION['id']) && isset($input['kegiatan_id'])) {
        $user_id = $_SESSION['id'];
        $kegiatan_id = $input['kegiatan_id'];

        // Ambil siswa_id berdasarkan user_id
        $siswa_id = null;
        $sql_siswa = "SELECT id FROM siswa WHERE user_id = ?";
        if ($stmt_siswa = mysqli_prepare($link, $sql_siswa)) {
            mysqli_stmt_bind_param($stmt_siswa, "i", $user_id);
            mysqli_stmt_execute($stmt_siswa);
            $result_siswa = mysqli_stmt_get_result($stmt_siswa);

            if ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
                $siswa_id = $row_siswa['id'];
            }
            mysqli_stmt_close($stmt_siswa);
        }

        if ($siswa_id) {
            // Periksa apakah checkpoint sudah dilakukan hari ini
            $tanggal_sekarang = date('Y-m-d');
            $sql_check = "SELECT * FROM checkpoint_kegiatan WHERE siswa_id = ? AND kegiatan_id = ? AND DATE(waktu_checkpoint) = ?";
            if ($stmt_check = mysqli_prepare($link, $sql_check)) {
                mysqli_stmt_bind_param($stmt_check, "iss", $siswa_id, $kegiatan_id, $tanggal_sekarang);
                mysqli_stmt_execute($stmt_check);
                $result_check = mysqli_stmt_get_result($stmt_check);

                if (mysqli_num_rows($result_check) > 0) {
                    // Jika checkpoint sudah dilakukan hari ini
                    echo json_encode(['success' => false, 'message' => 'Anda sudah melakukan checkpoint untuk kegiatan ini hari ini.']);
                    exit;
                }
                mysqli_stmt_close($stmt_check);
            }

            // Catat checkpoint
            $timestamp = date('Y-m-d H:i:s'); // Waktu sekarang sesuai zona waktu yang diatur

            // Ambil jam mulai dan jam selesai kegiatan
            $jam_mulai = null;
            $jam_selesai = null;
            $sql_kegiatan = "SELECT jam_mulai, jam_selesai FROM kegiatan WHERE id = ?";
            if ($stmt_kegiatan = mysqli_prepare($link, $sql_kegiatan)) {
                mysqli_stmt_bind_param($stmt_kegiatan, "s", $kegiatan_id);
                mysqli_stmt_execute($stmt_kegiatan);
                $result_kegiatan = mysqli_stmt_get_result($stmt_kegiatan);

                if ($row_kegiatan = mysqli_fetch_assoc($result_kegiatan)) {
                    $jam_mulai = $row_kegiatan['jam_mulai'];
                    $jam_selesai = $row_kegiatan['jam_selesai'];
                }
                mysqli_stmt_close($stmt_kegiatan);
            }

            // Tentukan status berdasarkan waktu checkpoint, jam mulai, dan jam selesai
            if ($jam_mulai && $jam_selesai) {
                $checkpoint_time = strtotime($timestamp);
                $start_time = strtotime(date('Y-m-d') . ' ' . $jam_mulai);
                $end_time = strtotime(date('Y-m-d') . ' ' . $jam_selesai);

                // Jika jam selesai lebih kecil dari jam mulai, tambahkan 1 hari ke jam selesai
                if ($end_time < $start_time) {
                    $end_time = strtotime('+1 day', $end_time);
                }

                // Tambahkan 10 menit sebagai toleransi keterlambatan
                $grace_end_time = strtotime('+10 minutes', $end_time);

                if ($checkpoint_time >= $start_time && $checkpoint_time <= $end_time) {
                    $status = 'Tepat Waktu';
                } elseif ($checkpoint_time > $end_time && $checkpoint_time <= $grace_end_time) {
                    $status = 'Terlambat';
                } else {
                    echo json_encode(['success' => false, 'message' => 'Waktu checkpoint telah berakhir.']);
                    exit;
                }
            } else {
                $status = 'Tepat Waktu'; // Default jika jam mulai atau jam selesai tidak ditemukan
            }


            // Simpan checkpoint ke database
            $sql_checkpoint = "INSERT INTO checkpoint_kegiatan (siswa_id, kegiatan_id, waktu_checkpoint, status) VALUES (?, ?, ?, ?)";
            if ($stmt_checkpoint = mysqli_prepare($link, $sql_checkpoint)) {
                mysqli_stmt_bind_param($stmt_checkpoint, "isss", $siswa_id, $kegiatan_id, $timestamp, $status);
                if (mysqli_stmt_execute($stmt_checkpoint)) {
                    echo json_encode(['success' => true, 'timestamp' => $timestamp, 'status' => $status]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mencatat checkpoint.']);
                }
                mysqli_stmt_close($stmt_checkpoint);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid.']);
}
