<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = [
        "success" => false
    ];


    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // attempt to log out of current user, delete their session (if not handled in class),
        // and update $response["success"] as needed
        if(isset($_SESSION['user'])){
            $response["success"] = true;
            unset($_SESSION['user']);
            setcookie(session_name(), "", time()-42000);
            session_destroy();

        }
    }

    // Use json_encode() to return $response
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>