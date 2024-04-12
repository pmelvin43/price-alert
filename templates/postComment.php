<?php
// Start the session to access session variables
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitComment'])) {
    // Database credentials
    $servername = "localhost";
    $dbUsername = "25141755";
    $password = "25141755";
    $dbname = "db_25141755";

    // Create a new connection to the database
    $connection = new mysqli($servername, $dbUsername, $password, $dbname);

    // Check the connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Get the submitted comment text and the product ID
    $commentText = $connection->real_escape_string(trim($_POST['commentText']));
    $productId = $connection->real_escape_string(trim($_POST['productId']));

    // Assuming you already have the username from session or other method
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'anonymous';

    // Prepare an INSERT statement to add the new comment
    $stmt = $connection->prepare("INSERT INTO comments (username, productID, commentText, postedDateTime) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sis", $username, $productId, $commentText);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        // Successfully inserted the comment
        header('Location: productpage.php'); // Redirect to product page after successful insertion
        exit(); // Always call exit after header redirection to prevent further code execution
    } else {
        echo "Error posting comment: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $connection->close();
} else {
    // If not a POST request or the form wasn't submitted correctly
    echo "Invalid request.";
}
?>
