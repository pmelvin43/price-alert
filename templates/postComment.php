<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitComment'])) {
    // Database credentials
    $servername = "localhost";
    $dbUsername = "25141755";
    $password = "25141755";
    $dbname = "db_25141755";


    $connection = new mysqli($servername, $dbUsername, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $commentText = $connection->real_escape_string(trim($_POST['commentText']));
    $productId = $connection->real_escape_string(trim($_POST['productId']));

    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'anonymous';


    $stmt = $connection->prepare("INSERT INTO comments (username, productID, commentText, postedDateTime) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sis", $username, $productId, $commentText);


    if ($stmt->execute()) {
        // Successfully inserted the comment
        header('Location: productpage.php'); // Redirect to product page after successful insertion
        exit(); // Always call exit after header redirection to prevent further code execution
    } else {
        echo "Error posting comment: " . $stmt->error;
    }


    $stmt->close();
    $connection->close();
} else {
    // If not a POST request or the form wasn't submitted correctly
    echo "Invalid request.";
}
?>
