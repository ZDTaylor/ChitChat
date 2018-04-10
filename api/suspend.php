<?php
    require_once "../lib/UserManager.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = [
        "success" => false
    ];


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data["userid"]) && !empty($data["datetime"])) {

            // pass userid and datetime to usermanager suspend and
            // update $response["success"] accordingly

            // $data["userid"] is the user to suspend, current user should be in session variable

        }
    }

    // Use json_encode() to return $response
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>