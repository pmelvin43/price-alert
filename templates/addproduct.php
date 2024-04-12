<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["productName"], $_POST["productPrice"], $_POST["description"], $_FILES["productPicture"])) {
        $productName = mysqli_real_escape_string($connection, $_POST["productName"]);
        $productPrice = mysqli_real_escape_string($connection, $_POST["productPrice"]);
        $description = mysqli_real_escape_string($connection, $_POST["description"]);

        // Handle file upload
        if ($_FILES['productPicture']['error'] == 0) {
            $productPicture = addslashes(file_get_contents($_FILES['productPicture']['tmp_name']));
        } else {
            echo "<script>alert('Error in file upload.'); window.location.href = 'home.php';</script>";
            exit();
        }

        $add = "INSERT INTO product (productName, price, description, productPicture) VALUES ('$productName', '$productPrice', '$description', '$productPicture')";

        if (mysqli_query($connection, $add)) {
            echo "<script>alert('New product added successfully.'); window.location.href = 'home.php';</script>";
        } else {
            $error = mysqli_error($connection);
            echo "<script>alert('Error adding product: " . addslashes($error) . "'); window.location.href = 'home.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid request'); window.location.href = 'home.php';</script>";
    }
}

$connection->close();
?>
