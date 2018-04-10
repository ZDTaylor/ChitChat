<?php
    require_once "../lib/Messenger.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $Messenger = new Messenger();
    $response = [
        "success" => false
    ];


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data["message"])) {

            // Create a message object, and update the required fields with the same fields from $data["message"]
            // attempt to post the message and update $response["success"] accordingly

        }

    }

    echo json_encode($response);
?>