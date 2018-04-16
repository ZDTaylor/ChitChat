<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/Messenger.php";
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $Messenger = new Messenger();
    $response = [
        "success" => false
    ];

    session_start();

    if(isset($_SESSION["user"])) {
        $_SESSION["user"] = $userManager->checkBannedSuspended($_SESSION["user"]);
        if ($_SESSION["user"]->banned || new DateTime() < $_SESSION["user"]->suspended) {
            unset($_SESSION["user"]);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data["messageid"])) {

            // attempt to dislike the message with $data["messageid"] and update $response["success"] accordingly

        }

    }

    echo json_encode($response);
?>