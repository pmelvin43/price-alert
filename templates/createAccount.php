<?php
// Database connection details
require_once 'db_connect.php';


// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and assign POST data to variables
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Password storage
    // Hash the password using md5
    $hashedPassword = md5($password);

    // Check if user already exists with the same username or email
    $stmt = $connection->prepare("SELECT COUNT(*) as usercount FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If user with the same username or email exist, automatically go back
    if ($row['usercount'] > 0) {
            
            echo '<script type="text/javascript">';
            echo 'alert("User already exist");';
            echo 'window.location.href="create-account.html";';
            echo '</script>';
            die();
    }

    // User creation in DB
    // Prepare INSERT statement since no user exists with the same username or email
    $stmt = $connection->prepare("INSERT INTO users (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)");

    // Bind parameters and execute the statement
    $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $hashedPassword);
    $stmt->execute();

    // echo "An account for the user ".$username. " has been created";

    $stmt->close();

    // Close Database
    mysqli_close($connection);

    // Go to next page
    echo '<script type="text/javascript">';
    echo 'alert("User created");';
    echo 'window.location.href="https://cosc360.ok.ubc.ca/yiuunamn/login.html";';
    echo '</script>';
    die();
} else {
    // The condition for bad data being injected via a GET request
    die('This page does not accept GET requests.');
}
?>
