<?php
    require_once "../lib/UserManager.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = ["success" => false];

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["email"]) && isset($data["password"])) {
        $email = sanitize_input($data["email"]);
        $password = sanitize_input($data["password"]);

        // $response should be a user object or false if there was an issue
        //$user = $userManager->login($email, $password);


        if ($password == "testpass") {
            session_start();
            $_SESSION["user"] = $email;
            $response["success"] = true;
            $response["user"] = "Zack Taylor";
        }

    }

    // Use json_encode() to return whatever we decide needs to be returned
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>