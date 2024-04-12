<!DOCTYPE html>
<html>


<?php

$servername = "localhost";
$username = "26780833";
$password = "26780833";
$dbname = "db_25141755";
$connection = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error()) {
    echo "<p>Unable to connect to database!</p>";
    exit();
}



?>
</html>
