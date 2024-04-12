<?php
session_start();

// Check if the logout button was clicked
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to the login page or homepage
    header("Location: login.php");
    exit;
}

// Redirect back if accessed the logout page without clicking the logout button
header("Location: index.php"); 
?>
