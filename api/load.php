<?php
    require_once "../lib/Messenger.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $Messenger = new Messenger();
    $response = [
        "success" => false,
        "messages" => []
    ];


    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // load messages and store them in $response["messages"]
        // if load is successful, update $response["success"] to be true

    }

    echo json_encode($response);
?>