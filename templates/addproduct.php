<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials should be kept in a separate, secure file
// require 'config.php';

$servername = "localhost";
$username = "25141755";  
$password = "25141755"; 
$dbname = "db_25141755"; 

// Establish database connection
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Unable to connect to database: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productName'], $_POST['productPrice'], $_POST['description'])) {
    $productName = mysqli_real_escape_string($connection, $_POST['productName']);
    $productPrice = mysqli_real_escape_string($connection, $_POST['productPrice']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);

    // Using prepared statements to prevent SQL Injection
    $stmt = $connection->prepare("INSERT INTO product (productName, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $productName, $productPrice, $description); // 's' for string, 'd' for double
    if ($stmt->execute()) {
        // Redirect to avoid form resubmission
        header("Location: home.jsp?message=Product+Uploaded+Successfully");
        exit();
    } else {
        echo "Error adding product: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request";
}

$connection->close();
?>
