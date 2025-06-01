<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Debugging: Check if session is destroyed
if (session_status() === PHP_SESSION_NONE) {
    echo "Session destroyed successfully.";
} else {
    echo "Failed to destroy session.";
}

// Redirect to login page
header("location: auth-login.php");
exit;
?>