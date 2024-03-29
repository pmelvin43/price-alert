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

    if (mysqli_connect_error()) {
        echo "<p>Unable to connect to database!</p>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["productName"], $_POST["productPrice"], $_POST["description"])) {
            $stmt = $connection->prepare("INSERT INTO product (productName, productPrice, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $productName, $productPrice, $description);

            $productName = $_POST["productName"];
            $productPrice = $_POST["productPrice"];
            $description = $_POST["description"];

            if ($stmt->execute()) {
                echo "<script>alert('New product added successfully.'); window.location.href = 'home.php';</script>";
            } else {
                $errorMsg = mysqli_real_escape_string($connection, mysqli_error($connection));
                echo "<script>alert('Error adding product: $errorMsg'); window.location.href = 'home.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Invalid request'); window.location.href = 'home.php';</script>";
        }
    }

    $connection->close();
    ?>
</body>
</html>
