<?php
    require_once "../lib/UserManager.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = false;
    // Check for variables that got posted, and use userManager to try to login.

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = sanitize_input($_POST["username"]);
        $password = sanitize_input($_POST["password"]);

        // $response should be a user object or false if there was an issue
        $user = $userManager->login($username, $password);

        if ($user != false) {
            session_start();
            $_SESSION["user"] = $user;
            $response = true;
        }

    }

    // Use json_encode() to return whatever we decide needs to be returned
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>