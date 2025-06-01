<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'monitor_siswa');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu Anda

$gmailid = ''; // YOUR gmail email
$gmailpassword = ''; // YOUR gmail App password
$gmailusername = ''; // YOUR gmail Username

?>