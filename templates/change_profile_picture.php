<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to change your profile picture.');
}

require_once 'db_connect.php';

echo '<script type="text/javascript">';
echo 'alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed.");';
echo '</script>';

if (isset($_POST['submit']) && isset($_FILES['profilePicture'])) {
    $username = $_SESSION['username'];
    $image = $_FILES['profilePicture']['tmp_name'];

    // Validate the image file
    $fileType = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (!in_array(strtolower($fileType), $allowTypes)) {
        echo '<script type="text/javascript">';
        echo 'alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed.");';
        echo 'window.location.href="change_profile_picture.html";';
        echo '</script>';
        exit;
    }

    // Read the content of the file
    $imageContent = file_get_contents($image);
    if ($imageContent === false) {
        echo '<script type="text/javascript">';
        echo 'alert("Failed to read the image file.");';
        echo 'window.location.href="change_profile_picture.html";';
        echo '</script>';
        exit;
    }

    // Prepare the statement to insert the BLOB into the database
    $stmt = $connection->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
    // Bind the blob and username
    $null = null; // Placeholder for the blob data
    $stmt->bind_param("bs", $null, $username);
    $stmt->send_long_data(0, $imageContent); // Sending the blob data
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '<script type="text/javascript">';
        echo 'alert("The profile picture has been updated successfully.");';
        echo 'window.location.href="userDash.php";';
        echo '</script>';
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Failed to update profile picture. It's possible that the profile picture is the same as before.");';
        echo 'window.location.href="change_profile_picture.html";';
        echo '</script>';
    }

    $stmt->close();
} else {
    echo '<script type="text/javascript">';
    echo 'alert("No file submitted.");';
    echo 'window.location.href="change_profile_picture.html";';
    echo '</script>';
}

$connection->close();
?>
