<!DOCTYPE html>
<html>


<?php

$host = "localhost";
$database = "db_25141755";
$user = "25141755";
$password = "25141755";

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
}

?>
</html>
