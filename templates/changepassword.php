<?php

// Database connection details
require_once 'db_connect.php';

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Bad data handling/closing
    die("This page only accepts POST requests.");
}

$username = mysqli_real_escape_string($connection, $_POST['username']);
// Hashing old password with md5
$oldPassword = md5($_POST['old-password']);
// Hashing new password with md5
$newPassword = md5($_POST['password']);

// Verify old password in DB
$stmt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $oldPassword);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo '<script type="text/javascript">';
    echo 'alert("wrong old password");';
    echo 'window.location.href="changepassword.html";';
    echo '</script>';
    die();
}

// Update to new password
$stmt = $connection->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $newPassword, $username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo '<script type="text/javascript">';
    echo 'alert("password changed");';
    echo 'window.location.href="login.html";';
    echo '</script>';
    die();
} else {
    echo '<script type="text/javascript">';
    echo 'alert("password update failed");';
    echo 'window.location.href="changepassword.html";';
    echo '</script>';
    die();
}

// Close Database
mysqli_close($connection);

?>
