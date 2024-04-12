<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details - Price Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
    <link rel="stylesheet" href="../static/css/productpage.css" />
</head>
<body>
<header id="masthead">
    <div id="searchbar">
        <form action="/search" method="get">
            <input type="text" id="search" name="search" placeholder="Find Amazon Products"/>
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
    <?php
    $servername = "localhost";
    $dbUsername = "25141755"; // Make sure this is not the same as your form input name if you are using $_POST or $_GET
    $password = "25141755";
    $dbname = "db_25141755";


    $connection = new mysqli($servername, $dbUsername, $password, $dbname);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $product_id = 2; 

    $stmt = $connection->prepare("SELECT productName, price, description, productPicture FROM product WHERE productId = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imgData = base64_encode($row['productPicture']);
        echo '<div><img src="data:image/jpeg;base64,' . $imgData . '" alt="Product Image" width="300" height="200" />';
        echo '<div><h2>Product Name: ' . htmlspecialchars($row['productName']) . '</h2>';
        echo '<p>Product Description: ' . htmlspecialchars($row['description']) . '</p>';
        echo '<h3>Current Price: $' . htmlspecialchars($row['price']) . '</h3></div></div>';
    } else {
        echo "Product not found.";
    }
    $stmt->close();


    $comment_stmt = $connection->prepare("SELECT username, commentText FROM comments WHERE productId = ?");
    $comment_stmt->bind_param("i", $product_id);
    $comment_stmt->execute();
    $comment_result = $comment_stmt->get_result();

    echo '<div><h3>User Comments</h3>';
    if ($comment_result->num_rows > 0) {
        while ($comment_row = $comment_result->fetch_assoc()) {
            echo '<div class="comment">';
            echo '<p><strong>' . htmlspecialchars($comment_row['username']) . ':</strong> ';
            echo htmlspecialchars($comment_row['commentText']) . '</p>';
            echo '</div>';
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    echo '</div>'; // Close the user comments section
    $comment_stmt->close();


    $connection->close();
    ?>

    <div>
    <h3>Post a Comment</h3>
    <form action="postComment.php" method="post">
    <textarea name="commentText" placeholder="Your comment" required></textarea>
    <input type="hidden" name="productId" value="<?php echo $product_id; ?>" />
    <button type="submit" name="submitComment" style="all: revert;">Submit Comment</button>
    </form>

    </div>

    <div>
        <h3>Price History</h3>
        <p>Graph of Price History goes here</p>
    </div>

</div>
</body>
</html>
