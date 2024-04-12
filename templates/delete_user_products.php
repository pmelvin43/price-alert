<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$productIdToDelete = $_POST['deleteProductId'];

$deleteStmt = $connection->prepare("DELETE FROM userProducts WHERE username=? AND productId=?");
$deleteStmt->bind_param("ss", $username, $productIdToDelete);
$deleteStmt->execute();

header('Location: userDash.php'); // Redirect back to dashboard
$connection->close();
?>
