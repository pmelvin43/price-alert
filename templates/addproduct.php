<!DOCTYPE html>
<html>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "25141755";
    $password = "25141755";
    $dbname = "db_25141755";
    $connection = new mysqli($servername, $username, $password, $dbname);

    if(mysqli_connect_error()) {
        echo "<p>Unable to connect to database!</p>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["productName"], $_POST["productPrice"], $_POST["description"])) {
            $productName = mysqli_real_escape_string($connection, $_POST["productName"]);
            $productPrice = mysqli_real_escape_string($connection, $_POST["productPrice"]);
            $description = mysqli_real_escape_string($connection, $_POST["description"]);

            $add = "INSERT INTO product (productName, productPrice, description) VALUES ('$productName', '$productPrice', '$description')";

            if (mysqli_query($connection, $add)) {
                echo "<script>alert('New product added successfully.'); window.location.href = 'home.jsp';</script>";
            } else {
                $error = mysqli_error($connection);
                echo "<script>alert('Error adding product: " . addslashes($error) . "'); window.location.href = 'home.jsp';</script>";
            }
        } else {
            echo "<script>alert('Invalid request'); window.location.href = 'home.jsp';</script>";
        }
    }

    $connection->close();
    ?>
</body>
</html>
