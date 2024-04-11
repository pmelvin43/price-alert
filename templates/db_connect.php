<!DOCTYPE html>
<html>


<?php

$connection = mysqli_connect("localhost","26780833","26780833","db_26780833");
### OR
$servername = "localhost"; 
$username = "26780833";
$password = "26780833"; 
$dbname = "db_26780833"; 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname)

$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
}

?>
</html>
