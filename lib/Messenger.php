<?php

require_once "lib/login.php";
require_once "lib/User.php";
require_once "lib/Message.php";

class Messenger {
    private $database;
    //Default constructor for Messenger class
    function __construct() {
        global $db_login;
        $this->database = new mysqli($db_login["hn"], $db_login["un"], $db_login["pw"], $db_login["db"]);
        if ($this->database->connect_error) {
            die($this->database->connect_error);
        }
    }

    //Load function - The load function will load a set of messages from the database and return an array of Message objects.
    function load() {
        $query = "SELECT messageID, userID, content FROM Messages";
        $messageID;
        $userID;
        $content;
        $messages = [];

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare($query)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->bind_result($messageID, $userID, $content);
            while ($stmt->fetch()) {
                $messages[] = [
                    "messageID" => $messageID,
                    "userID" => $userID,
                    "content" => $content
                ];
            }
            $stmt->close();
            return $messages;
        }
        else {
            $stmt->close();
            return false;
        }
    }
}

?>