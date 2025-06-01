<?php
session_start();
require_once '../../Starterkit/partials/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Hapus data dari tabel siswa
        $siswa_sql = "DELETE FROM siswa WHERE user_id = ?";
        if ($siswa_stmt = mysqli_prepare($link, $siswa_sql)) {
            mysqli_stmt_bind_param($siswa_stmt, "i", $user_id);
            if (mysqli_stmt_execute($siswa_stmt)) {
                // Jika berhasil, hapus data dari tabel users
                $user_sql = "DELETE FROM users WHERE id = ?";
                if ($user_stmt = mysqli_prepare($link, $user_sql)) {
                    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
                    if (mysqli_stmt_execute($user_stmt)) {
                        $_SESSION['success'] = "Data siswa berhasil dihapus.";
                    } else {
                        $_SESSION['error'] = "Gagal menghapus data pengguna.";
                    }
                    mysqli_stmt_close($user_stmt);
                }
            } else {
                $_SESSION['error'] = "Gagal menghapus data siswa.";
            }
            mysqli_stmt_close($siswa_stmt);
        }
    } else {
        $_SESSION['error'] = "ID pengguna tidak ditemukan.";
    }

    mysqli_close($link);
    header("location: ../daftar_siswa.php");
    exit;
}
?>