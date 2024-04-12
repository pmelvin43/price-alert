<?php
// Start the session and check user authentication
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

// Include database connection
require_once 'db_connect.php';
$username = $_SESSION['username'];

// Fetch user details
$stmt = $connection->prepare("SELECT firstName, lastName, email, profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
    $profilePicture = $userDetails['profile_picture'] ? 'data:image/jpeg;base64,' . base64_encode($userDetails['profile_picture']) : 'default/default.jpg';
} else {
    echo "User not found.";
    $connection->close();
    exit();
}

// Fetch user's saved products
$productStmt = $connection->prepare("SELECT p.productId, p.productName, p.price FROM userProducts up JOIN product p ON up.productId = p.productId WHERE up.username = ?");
$productStmt->bind_param("s", $username);
$productStmt->execute();
$productResult = $productStmt->get_result();
$products = [];
while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
}

// Fetch all available products
$productQuery = "SELECT productId, productName, price, description, productPicture FROM product";
$allProducts = $connection->query($productQuery);

// Close all statements and connections
$stmt->close();
$productStmt->close();
$connection->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details - Price Tracker</title>
    <link rel="stylesheet" type="text/css" href="../static/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="../static/css/productpage.css" />
</head>
<body>
<header id="masthead">
    <div id="searchbar">
        <form action="/search" method="get">
            <input type="text" id="search" name="search" placeholder="Find Amazon Products" />
            <button type="submit">Search</button>
        </form>
    </div>
    <div id="price-alert-button">
        <a href="../home.php" class="button-link"><h1>Price Alert</h1></a>
    </div>
    <div id="register-button">
        <a href="login.html">User Account</a>
    </div>
    <form action="logout.php" method="post">
        <button type="submit" name="logout">Log Out</button>
    </form>
</header>
<br />
<div class="content-container">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 100px; height: 100px; background-color: white;">
    <a href="change_profile_picture.php">Change Profile Picture</a>
    <p>First Name: <?php echo htmlspecialchars($userDetails['firstName']); ?></p>
    <p>Last Name: <?php echo htmlspecialchars($userDetails['lastName']); ?></p>
    <p>Email: <?php echo htmlspecialchars($userDetails['email']); ?></p>

    <!-- Display User's Saved Products -->
    <h2>Your Saved Products</h2>
    <form action="delete_user_products.php" method="post">
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <?php echo htmlspecialchars($product['productName']); ?> - $<?php echo htmlspecialchars($product['price']); ?>
                    <button type="submit" name="deleteProductId" value="<?php echo $product['productId']; ?>">Delete</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>

    <!-- Display Available Products -->
    <h2>Available Products</h2>
    <form action="add_user_products.php" method="post">
        <div class="product-container" style="height: 200px; overflow-y: scroll;">
            <?php while ($product = $allProducts->fetch_assoc()): ?>
                <div>
                    <input type="checkbox" name="productIds[]" value="<?php echo $product['productId']; ?>">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['productPicture']); ?>" style="width:50px; height:50px;"> 
                    <?php echo htmlspecialchars($product['productName']) . ' - $' . htmlspecialchars($product['price']); ?>
                </div>
            <?php endwhile; ?>
        </div>
        <button type="submit">Add Selected Products</button>
    </form>
</div>
</body>
</html>

