<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/Messenger.php";
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $Messenger = new Messenger();
    $response = [
        "messageID" => null,
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

            if (!empty($data["message"])) {

                // Create a message object, and update the required fields with the same fields from $data["message"]
                // attempt to post the message and update $response["success"] accordingly
                $message = new Message();
                $message->poster = $_SESSION["user"]->userID;
                $message->content = $data["message"]["content"];
                $message->mentions = $data["message"]["mentions"];

                $message->mentions = array_unique($message->mentions);

                if (!empty($message->content)) {
                    $post = $Messenger->post($message);

                    if ($post !== false) {
                        $response["messageID"] = $post;
                        $response["success"] = true;
                    }
                }
            }
        }
    }


    echo json_encode($response);
?>