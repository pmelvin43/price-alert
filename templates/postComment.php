<?php
// Start the session to access session variables
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitComment'])) {
    // Database credentials
    $servername = "localhost";
    $dbUsername = "25141755"; // Your database username
    $password = "25141755"; // Your database password
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
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Retrieved from session
    } else {
        // Handle case where no user is logged in or session is not set
        $username = 'anonymous'; // Consider how you want to handle this case
    }

    // Prepare an INSERT statement to add the new comment
    $stmt = $connection->prepare("INSERT INTO comments (username, productId, commentText) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $username, $productId, $commentText);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "Comment posted successfully!";
    } else {
        echo "Error posting comment: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $connection->close();

    // Redirect back to the product page or a confirmation page
    header('Location: productpage.php'); // Adjust the redirect as necessary
} else {
    // If not a POST request or the form wasn't submitted correctly
    echo "Invalid request.";
}
?>
