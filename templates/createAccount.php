<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "25141755";
    $password = "25141755";
    $dbname = "db_25141755";
    $connection = new mysqli($servername, $username, $password, $dbname);
    if(mysqli_connect_error()) {
        echo "<p>Unable to connect to database!</p>";
        echo "<p><a href='javascript:history.back()'>Return to User Entry</a></p>";
        exit();
    }
    // Sanitize and assign POST data to variables
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $hashedPassword = md5($password);

    // Initialize variables for the profile picture
    $profilePicture = null;
    $profilePictureType = null;

    // Check and process the uploaded file
    if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] == 0) {
        $allowedTypes = ['jpg' => 'image/jpg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
        $fileType = $_FILES['userImage']['type'];
        if (in_array($fileType, $allowedTypes)) {
            // Read the file's contents into a variable
            $profilePicture = file_get_contents($_FILES['userImage']['tmp_name']);
            $profilePictureType = $fileType;
        } else {
            die('Invalid file type. Only JPG, JPEG, and PNG are allowed.');
        }
    }

            // Image resizing
            list($originalWidth, $originalHeight) = getimagesize($_FILES['userImage']['tmp_name']);
            $targetWidth = 200; // Set the target width
            $targetHeight = (int) ($originalHeight * $targetWidth / $originalWidth); // Maintain aspect ratio
    
            $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
    
            // Based on file type, create a new image from the file
            switch ($fileType) {
                case 'image/jpeg':
                    $originalImage = imagecreatefromjpeg($_FILES['userImage']['tmp_name']);
                    break;
                case 'image/png':
                    $originalImage = imagecreatefrompng($_FILES['userImage']['tmp_name']);
                    break;
                default:
                    die('Unsupported file type.');
            }
    
            // Resize the image
            imagecopyresampled($targetImage, $originalImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);
            
            // Save the resized image to a temporary location
            $tempImagePath = tempnam(sys_get_temp_dir(), 'resized_');
            switch ($fileType) {
                case 'image/jpeg':
                    imagejpeg($targetImage, $tempImagePath, 90); // Save as JPEG
                    break;
                case 'image/png':
                    imagepng($targetImage, $tempImagePath, 9); // Save as PNG
                    break;
            }
    
            $profilePicture = file_get_contents($tempImagePath);
            $profilePictureType = $fileType;
    
            // Clean up
            imagedestroy($originalImage);
            imagedestroy($targetImage);
            unlink($tempImagePath); // Delete the temp file

    // Check if the user already exists
    $stmt = $connection->prepare("SELECT COUNT(*) as usercount FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['usercount'] > 0) {
        echo '<script>alert("User already exists"); window.location.href="create-account.html";</script>';
        exit;
    }

    // Insert the new user into the database, including the profile picture
    $stmt = $connection->prepare("INSERT INTO users (firstname, lastname, username, email, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
    // The 'b' type is not directly supported in mysqli's bind_param, so the data is passed as string 's'
    $stmt->bind_param("ssssss", $firstname, $lastname, $username, $email, $hashedPassword, $profilePicture);
    $stmt->send_long_data(5, $profilePicture); // For sending BLOB data
    $stmt->execute();

    echo '<script>alert("User created successfully"); window.location.href="login.html";</script>';
    $stmt->close();
    mysqli_close($connection);
} else {
    die('This page does not accept GET requests.');
}
?>
