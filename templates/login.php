
<!DOCTYPE html>
<html>
<body>
    <?php
        session_start();
        $servername = "localhost";
        $username = "25141755";
        $password = "25141755";
        $dbname = "db_25141755";
        $connection = new mysqli($servername, $username, $password, $dbname);
           if(mysqli_connect_error()) {
        echo "<p>Unable to connect to database!</p>";
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = mysqli_real_escape_string($connection,$_POST["username"]);
            $password = mysqli_real_escape_string($connection,$_POST["password"]);

            $hashed_password = md5($password);
            $login="SELECT * FROM users WHERE username = '$username' AND password ='$hashed_password'";
            $result=mysqli_query($connection, $login);

            if($result&&mysqli_num_rows($result) > 0) {
                echo "<p>User has a valid account.</p>";
            } else {
                echo "<p>Invalid username and/or password.</p>";
                echo "<p><a href='javascript:history.back()'>Return to Login</a></p>";
            }
        } else {
            echo "<p>Username and password required.</p>";
            echo "<p><a href='javascript:history.back()'>Return to Login</a></p>";
        }
    } 
    else {
        echo "<p>Invalid request.</p>";
    }

    mysqli_close($connection);
    ?>
</body>
</html>
