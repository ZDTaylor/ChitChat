<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/Messenger.php";
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-Type: text/event-stream'); // specific sse mimetype
    header('Cache-Control: no-cache'); // no cacheheader('X-Accel-Buffering: no');
    header('X-Accel-Buffering: no');

    // function disable_gzip() {
    //     @ini_set('zlib.output_compression', 'Off');
    //     @ini_set('output_buffering', 'Off');
    //     @ini_set('output_handler', '');
    //     @apache_setenv('no-gzip', 1);
    // }

    // disable_gzip();
    ob_end_clean();

    set_time_limit(0);

    $userManager = new UserManager();
    $Messenger = new Messenger();
    $response = [
        "success" => false,
        "messages" => []
    ];
    session_start();
    $old_messages = [];
    while(!connection_aborted()) {
        if(isset($_SESSION["user"])) {
            $_SESSION["user"] = $userManager->checkBannedSuspended($_SESSION["user"]);
            if ($_SESSION["user"]->banned || new DateTime() < $_SESSION["user"]->suspended) {
                unset($_SESSION["user"]);
            }
        }
        session_write_close();

        if (!empty($_SESSION["user"])) {
            $userID = $_SESSION["user"]->userID;
        }
        else {
            $userID = 0;
        }

        // load messages and store them in $response["messages"]
        // if load is successful, update $response["success"] to be true
        $messages = $Messenger->load($userID);

        if ($messages != false) {
            if (count($messages) != count($old_messages)) {
                $update = true;
            }
            else {
                for ($i = 0; $i < count($messages); $i++) {
                    if ($messages[$i] != $old_messages[$i]) {
                        $update = true;
                        break;
                    }
                }
            }
        }

        if($update == true){

            $response["success"] = true;
            $response["messages"] = $messages;
            $old_messages = $messages;
            $update = false;

            echo "data: ".json_encode($response).PHP_EOL;

            echo PHP_EOL;

        }
        ob_end_flush();
        ob_flush(); // clear memory
        flush(); // clear memory
        sleep(5);// seconds
    }
?>