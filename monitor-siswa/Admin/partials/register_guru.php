<?php
require_once '../../Starterkit/partials/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Generate token
    $token = bin2hex(random_bytes(50));

    $sql = "INSERT INTO users (username, useremail, password, role_user, token) VALUES ('$username', '$email', '$password', 1, '$token')";

    if (mysqli_query($link, $sql)) {
        $_SESSION['success'] = 'Guru berhasil ditambahkan.';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan guru: ' . mysqli_error($link);
    }

    // Redirect kembali ke halaman sebelumnya
    header('Location: ../list_guru.php');
    exit;
}
?>

<?php include '../partials/main.php'; ?>
