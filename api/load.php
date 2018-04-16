<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/Messenger.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $Messenger = new Messenger();
    $response = [
        "success" => false,
        "messages" => []
    ];

    session_start();

    if(isset($_SESSION["user"])) {
        $_SESSION["user"] = $userManager->checkBannedSuspended($_SESSION["user"]);
        if ($_SESSION["user"]->banned || new DateTime() < $_SESSION["user"]->suspended) {
            unset($_SESSION["user"]);
        }
    }

    if (!empty($_SESSION["user"])) {
        $userID = $_SESSION["user"]->userID;
    }
    else {
        $userID = 0;
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // load messages and store them in $response["messages"]
        // if load is successful, update $response["success"] to be true
        $messages = $Messenger->load($userID);

        if ($messages != false) {
            $response["messages"] = $messages;
            $response["success"] = true;
        }

    }

    echo json_encode($response);
?>