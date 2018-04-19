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

    if (!empty($_SESSION["user"])) {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $data = json_decode(file_get_contents("php://input"), true);

            if (!empty($data["messageID"])) {

                // attempt to like the message with $data["messageID"] and update $response["success"] accordingly
                $like = $Messenger->like($data["messageID"], $_SESSION["user"]->userID);

                if ($like != false){
                    $response["success"] = true;
                }
            }

        }
    }

    echo json_encode($response);
?>