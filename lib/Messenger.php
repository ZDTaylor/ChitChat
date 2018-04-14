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

    function post($message) {
        $query = "INSERT INTO Messages(content, userID) VALUES(?, ?)";

        if(!$stmt = $this->database->prepare($query)) {
            return false;
        }

        if (!$stmt->bind_param('si', $content, $userID)) {
            return false;
        }


        if ($stmt->execute()) {
            $stmt->close();
        //For mentions
        //$query = "INSERT INTO Mentions (userID, messageID) VALUES(?,?)";


            return true;
        }
        else{
            $stmt->close();
            return false;
        }

    }

    function edit($message) {
        // take in a message with an id and userID, and update the content of the message IF the userID matches the one in the DB
    }

    function delete($messageID) {
        // delete the message with messageID.  User checking will be done in the api file
    }

    function like($messageID, $userID) {
        // I will change the database to make this much simpler to do
    }

    function dislike($messageID, $userID) {
        // I will change the database to make this much simpler to do
    }
}

?>