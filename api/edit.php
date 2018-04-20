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

            if (!empty($data["message"])) {

                $data["message"]["content"] = sanitize_input($data["message"]["content"]);
                for ($i = 0; $i < count($data["message"]["mentions"]); $i++) {
                    $data["message"]["mentions"][$i] = intval($data["message"]["mentions"][$i]);
                }
                $data["message"]["messageID"] = intval($data["message"]["messageID"]);
                // Create a message object, and update the required fields with the same fields from $data["message"]
                // attempt to edit the message and update $response["success"] accordingly

                // Create quote tags
                while (strpos($data["message"]["content"], "[QUOTE]") !== false && strpos($data["message"]["content"], "[/QUOTE]") !== false) {
                    $data["message"]["content"] = str_replace("[QUOTE]", "<span class='cc-message-quote'>", $data["message"]["content"]);
                    $data["message"]["content"] = str_replace("[/QUOTE]", "</span>", $data["message"]["content"]);
                }

                // Fix nesting quotes
                while (strpos($data["message"]["content"], "&lt;span class='cc-message-quote'&gt;") !== false && strpos($data["message"]["content"], "&lt;/span&gt;") !== false) {
                    $data["message"]["content"] = str_replace("&lt;span class='cc-message-quote'&gt;", "<span class='cc-message-quote'>", $data["message"]["content"]);
                    $data["message"]["content"] = str_replace("&lt;/span&gt;", "</span>", $data["message"]["content"]);
                }

                $message = new Message();
                $message->poster = $_SESSION["user"]->userID;
                $message->messageID = $data["message"]["messageID"];
                $message->content = $data["message"]["content"];
                $message->mentions = $data["message"]["mentions"];

                $message->mentions = array_unique($message->mentions);

                if (!empty($message->content)) {
                    $edit = $Messenger->edit($message, $_SESSION["user"]->userID);

                    if ($edit !== false) {
                        $response["messageID"] = $edit;
                        $response["success"] = true;
                    }
                }
            }
        }
    }


    echo json_encode($response);
?>