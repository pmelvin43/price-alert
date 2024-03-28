<?php
// Start the session at the very beginning
session_start();

// Database connection details
require_once 'db_connect.php';

// Check if the form was submitted via post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize user inputs
    $username = mysqli_real_escape_string($connection, filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = mysqli_real_escape_string($connection, $_POST['password']); 
    // Hashing password with md5
    $hashedPassword = md5($password);

    // Prepare SELECT statement to fetch user with the username
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password against the hashed password in DB
        if ($hashedPassword == $user['password']) {
            // Password is correct, so start a new session and save the username to the session
            $_SESSION['username'] = $username;
            // echo 'Hi, '.$username.'<br>You are logged in.</a>';
        } else {
            // Password is not correct
            echo '<script type="text/javascript">';
            echo 'alert("Wrong Password");';
            echo 'window.location.href="login.html";';
            echo '</script>';
            die();
        }
    } else {
        // No user found with the username
        echo '<script type="text/javascript">';
        echo 'alert("User not found");';
        echo 'window.location.href="login.html";';
        echo '</script>';
        die();
    }

    $stmt->close();
    // Close Database
    mysqli_close($connection);

    // Go to next page
    header("Location:/index.php");
    exit();
} else {
    // The condition for bad data being injected via a GET request
    die('This page does not accept GET requests.');
}
?>
