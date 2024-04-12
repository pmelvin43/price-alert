<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile Picture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
    <link rel="stylesheet" href="../static/css/productpage.css" />
</head>
<body>
    <header id="masthead">
        <div id="searchbar">
        </div>
        <h1>Price Alert</h1>
    </header>

    <?php
    session_start();

    // Ensure the user is logged in
    if (!isset($_SESSION['username'])) {
        echo '<script type="text/javascript">alert("You must be logged in to change your profile picture.");</script>';
        exit;
    }

    require_once 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePicture'])) {
        $username = $_SESSION['username'];
        $image = $_FILES['profilePicture']['tmp_name'];

        // Validate the image file
        $fileType = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (!in_array(strtolower($fileType), $allowTypes)) {
            echo '<script type="text/javascript">alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed."); window.location.href="change_profile_picture.html";</script>';
            exit;
        }

        // Read the content of the file
        $imageContent = file_get_contents($image);
        if ($imageContent === false) {
            echo '<script type="text/javascript">alert("Failed to read the image file."); window.location.href="change_profile_picture.html";</script>';
            exit;
        }

        // Prepare the statement to insert the BLOB into the database
        $stmt = $connection->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
        // Bind the blob and username
        $null = null; // Placeholder for the blob data
        $stmt->bind_param("bs", $null, $username);
        $stmt->send_long_data(0, $imageContent); // Sending the blob data
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo '<script type="text/javascript">alert("The profile picture has been updated successfully."); window.location.href="userDash.php";</script>';
        } else {
            echo '<script type="text/javascript">alert("Failed to update profile picture. It\'s possible that the profile picture is the same as before."); window.location.href="change_profile_picture.html";</script>';
        }

        $stmt->close();
        $connection->close();
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <h2>Change Profile Picture</h2>
        <div class="login-container">
            <div class="input-group">
                Select image to upload:
                <input type="file" name="profilePicture" accept="image/*" required>
                <input type="submit" value="Upload Image" name="submit">
            </div>
        </div>
    </form>
</body>
</html>
