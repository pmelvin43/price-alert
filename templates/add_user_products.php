<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$productIds = $_POST['productIds'];

foreach ($productIds as $productId) {
    $insertStmt = $connection->prepare("INSERT INTO userProducts (username, productId) VALUES (?, ?) ON DUPLICATE KEY UPDATE productId=productId");
    $insertStmt->bind_param("ss", $username, $productId);
    $insertStmt->execute();
}

header('Location: userDash.php'); // Redirect back to dashboard
$connection->close();
?>
