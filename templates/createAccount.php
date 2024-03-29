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
        if(isset($_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['email'], $_POST['password'])) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
            $result = mysqli_query($connection, $checkQuery);
            if($result) {
                $user = mysqli_fetch_assoc($result);
                if ($user) {
                    if ($user['username'] === $username || $user['email'] === $email) {
                        echo "<p>User already exists with this name and/or email.</p>";
                        echo "<p><a href='javascript:history.back()'>Return to User Entry</a></p>";
                    }
                } else {
                    $hashed_password = md5($password);
                    $insert = "INSERT INTO users (firstname, lastname, username, email, password) VALUES ('$firstname', '$lastname', '$username', '$email', '$hashed_password')";

                    if(mysqli_query($connection, $insert)) {
                        echo "<p><a href='javascript:history.back()'>Login</a></p>";
                    } else {
                        echo "<p>Error: " . mysqli_error($connection) . "</p>";
                        echo "<p><a href='javascript:history.back()'>Return to User Entry</a></p>";
                    }
                }
            } else {
                echo "<p>Error checking user existence.</p>";
                echo "<p><a href='javascript:history.back()'>Return to User Entry</a></p>";
            }
            mysqli_free_result($result);
            mysqli_close($connection);
        } else {
            echo "<p>Error: All fields are required.</p>";
            echo "<p><a href='javascript:history.back()'>Return to User Entry</a></p>";
        }
    } else {
        echo "<p>Invalid request.</p>";
    }
    ?>
