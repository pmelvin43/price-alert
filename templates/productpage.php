<?php

$servername = "localhost";
$username = "25141755";
$password = "225141755";
$dbname = "db_25141755";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = 1; // You might want to make this dynamic

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT productName, price, description, productPicture FROM product WHERE productId = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // The header content type is not necessary since we're embedding the image in an HTML page
    // header("Content-Type: image/jpg");

    // Use base64_encode to encode the BLOB data
    $imgData = base64_encode($row['productPicture']);
    
    // Echo out the image within the img tag
    echo '<img src="data:image/jpeg;base64,' . $imgData . '" alt="Product Image" />';
    
    // Display product details
    echo '<h2>' . htmlspecialchars($row['productName']) . '</h2>';
    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
    echo '<h3>Current Price: $' . htmlspecialchars($row['price']) . '</h3>';

    // Other parts of your HTML/PHP script
} else {
    echo "Product not found.";
}

// Close the statement and the connection
$stmt->close();
$conn->close();

?>
