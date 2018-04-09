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

        if (!empty($data["messageid"])) {

            // attempt to like the message with $data["messageid"] and update $response["success"] accordingly

        }

    }

    echo json_encode($response);
?>