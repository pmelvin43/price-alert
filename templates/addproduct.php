<!DOCTYPE html>
<html>
<body>
    <?php
    session_start();
    $servername = "localhost";
    $username = "25141755";  
    $password = "25141755"; 
    $dbname = "db_25141755"; 

    // Create connection
    $connection = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if (mysqli_connect_error()) {
        echo "<script>alert('Unable to connect to database!'); window.location.href = 'home.jsp';</script>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize input data
        $productName = mysqli_real_escape_string($connection, $_POST['productName']);
        $productPrice = mysqli_real_escape_string($connection, $_POST['productPrice']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);

        // Insert data into the 'product' table
        $sql = "INSERT INTO product (productName, price, description) VALUES ('$productName', '$productPrice', '$description')";

        if (mysqli_query($connection, $sql)) {
            echo "<script>alert('New product added successfully.'); window.location.href = 'home.jsp';</script>";
        } else {
            echo "<script>alert('Error adding product: " . mysqli_error($connection) . "'); window.location.href = 'home.jsp';</script>";
        }
    } else {
        echo "<script>alert('Invalid request.'); window.location.href = 'home.jsp';</script>";
    }

    // Close connection
    mysqli_close($connection);
    ?>
</body>
</html>
