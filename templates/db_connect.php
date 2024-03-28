<!DOCTYPE html>
<html>
<body>
    <?php
        $servername = "localhost";
        $username = "25141755";
        $password = "25141755";
        $dbname = "db_25141755";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if(mysqli_connect_error()) {
            echo "<p>Unable to connect to database!</p>";
            echo "<p><a href='javascript:history.back()'>go back</a></p>";
            exit();
        }
