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
$stmt = $connection->prepare("SELECT firstName, lastName, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
} else {
    // Handle error - user not found (should not happen if session is valid)
    echo "User not found.";
    exit();
}

// Close the statement and connection
$stmt->close();
$connection->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details - Price Tracker</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
    />
    <link rel="stylesheet" href="../static/css/productPage.css" />
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
      <h1>Price Alert</h1>
      <div id="register-button">
        <a href="login.html">User Account</a>
      </div>
    </header>
    <br />
    <div class="content-container">
    
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>First Name: <?php echo htmlspecialchars($userDetails['firstName']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($userDetails['lastName']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userDetails['email']); ?></p>
        <a href="changepassword.html">Change Password</a>

    </div>
  </body>
</html>
