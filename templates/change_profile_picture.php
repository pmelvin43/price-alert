<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to change your profile picture.');
}

require_once 'db_connect.php';

if (isset($_POST['submit']) && isset($_FILES['profilePicture'])) {
    $username = $_SESSION['username'];
    $image = $_FILES['profilePicture']['tmp_name'];

    // Validate the image file
    $fileType = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (!in_array(strtolower($fileType), $allowTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
        exit;
    }

    // Read the content of the file
    $imageContent = file_get_contents($image);
    if ($imageContent === false) {
        echo "Failed to read the image file.";
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
        echo "The profile picture has been updated successfully.";
    } else {
        echo "Failed to update profile picture. It's possible that the profile picture is the same as before.";
    }

    $stmt->close();
} else {
    echo "No file submitted.";
}

$connection->close();
?>