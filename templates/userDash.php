<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header('Location: login.html');
    exit();
}

// Include database connection
require_once 'db_connect.php';

// Fetch user details from the database
$username = $_SESSION['username']; // The username stored in session
$stmt = $connection->prepare("SELECT firstName, lastName, email, profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $userDetails = $result->fetch_assoc();
  if ($userDetails['profile_picture']) {
      // If there's a profile picture, encode it for HTML display
      $profilePicture = 'data:image/jpeg;base64,' . base64_encode($userDetails['profile_picture']);
  } else {
      // User does not have a profile pic stored, use a default
      $profilePicture = 'default/default.jpg';
  }
} else {
  // Handle error - user not found
  echo "User not found.";
  exit();
}

// Fetch user's saved products
$productStmt = $connection->prepare("SELECT p.productName, p.price FROM userProducts up JOIN product p ON up.productId = p.productId WHERE up.username = ?");
$productStmt->bind_param("s", $username);
$productStmt->execute();
$productResult = $productStmt->get_result();

$products = [];
while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
}

// Close the statement and connection
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
          <input
            type="text"
            id="search"
            name="search"
            placeholder="Find Amazon Products"
          />
          <button type="submit">Search</button>
        </form>
      </div>

        <a href="../home.php" class="button-link">
          <h1>Price Alert</h1>
        </a>

      <div id="register-button">
        <a href="login.html">User Account</a>
      </div>
      <form action="logout.php" method="post">
      <div id="register-button">
        <button type="submit" name="logout">
          Log Out
        </button>
      </div>
    </form>
    </header>
    <br />
    <div class="content-container">
      <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
      
      <!-- Profile Picture or Default Pic-->
      <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 100px; height: 100px; background-color: white;">
      <a href="change_profile_picture.php">Change Profile Picture</a>


      <p>First Name: <?php echo htmlspecialchars($userDetails['firstName']); ?></p>
      <p>Last Name: <?php echo htmlspecialchars($userDetails['lastName']); ?></p>
      <p>Email: <?php echo htmlspecialchars($userDetails['email']); ?></p>

      <!-- User's Saved Products -->
      <h2>Saved Products</h2>
        <?php if (count($products) > 0): ?>
            <ul>
                <?php foreach ($products as $product): ?>
                    <li><?php echo htmlspecialchars($product['productName']); ?> - $<?php echo htmlspecialchars($product['price']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have no saved products.</p>
        <?php endif; ?>

      <a href="changepassword.html">Change Password</a>
    </div>
  </body>
</html>
