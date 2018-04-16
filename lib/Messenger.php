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
    function load($userID) {
        $query = "SELECT
        m.messageID,
        m.userID as poster,
        m.content,
        GROUP_CONCAT(DISTINCT n.userID ORDER BY n.userID ASC) AS mentions,
        SUM(r.reaction) as net_likes,
        IF(r.userID = ?, r.reaction, NULL) as reaction
        FROM Messages m
        LEFT JOIN Mentions n ON (m.messageID = n.messageID)
        LEFT JOIN Reactions r ON (m.messageID = r.messageID)
        GROUP BY m.messageID";

        $messageID;
        $poster;
        $content;
        $mentions;
        $net_likes;
        $reaction;

        $messages = [];

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare($query)) {
            return false;
        }

        if (!$stmt->bind_param('i', $userID)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->bind_result($messageID, $poster, $content, $mentions, $net_likes, $reaction);
            while ($stmt->fetch()) {
                $message = new Message();
                $message->messageID = $messageID;
                $message->content = $content;
                $message->poster = $userID;
                $message->net_likes = !is_null($net_likes) ? intval($net_likes) : 0;
                $message->mentions = !is_null($mentions) ? array_map('intval', explode(",", $mentions)) : [];
                $message->reaction = !is_null($reaction) ? intval($reaction) : 0;
                $messages[] = $message;
            }
            $stmt->close();
            return $messages;
        }
        else {
            $stmt->close();
            return false;
        }
    }

    function post($message){
        $error = false;
        $userID = $message->poster;
        $content = $message->content;
        $mentions = $message->mentions;

        // Begin Transaction
        $this->database->autocommit(FALSE);

        // Insert message into messages database
        $query = "INSERT INTO Messages(content, userID) VALUES(?, ?);";

        if(!$stmt = $this->database->prepare($query)){
            $error = true;
        }

        if (!$stmt->bind_param('si', $content, $userID)) {
            $error = true;
        }

        if ($stmt->execute()){
            $messageID = $stmt->insert_id;
            $stmt->close();

            if ($messageID) {

                if (count($mentions) != 0) {
                    //Insert mentions into mentions database
                    $query = "INSERT INTO Mentions (userID, messageID) VALUES(?,?);";

                    if(!$stmt = $this->database->prepare($query)){
                        $error = true;
                    }

                    if (!$stmt->bind_param('ii', $userID, $messageID)) {
                        $error = true;
                    }

                    foreach ($mentions as $userID) {
                        if (!$stmt->execute()) {
                            $error = true;
                        }
                    }
                    $stmt->close();
                }
            }
            else {
                $error = true;
            }
        }
        else {
            $error = true;
        }

        // Commit if no error, rollback otherwise
        if (!$error) {
            if($this->database->commit()) {
                $this->database->autocommit(TRUE);
                return true;
            }
            else {
                $this->database->rollback();
                $this->database->autocommit(TRUE);
                return false;
            }
        }
        else {
            $this->database->rollback();
            $this->database->autocommit(TRUE);
            return false;
        }
    }

    function edit($message) {
        // take in a message with an id and userID, and update the content of the message IF the userID matches the one in the DB
    }

    function delete($messageID, $userID) {
        // delete the message with messageID.  User checking will be done in the api file

        //Do during lab
        $query = "DELETE FROM Messages WHERE $userID = userID OR $userID = "; //How to identify Admin account?
        
        
    }

    function like($messageID, $userID) {
        $query = "INSERT INTO Reactions (messageID, userID, reaction)
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE
        reaction = IF(VALUES(reaction) = reaction, 0, VALUES(reaction))";
    }

    function dislike($messageID, $userID) {
        $query = "INSERT INTO Reactions (messageID, userID, reaction)
        VALUES (?, ?, -1)
        ON DUPLICATE KEY UPDATE
        reaction = IF(VALUES(reaction) = reaction, 0, VALUES(reaction))";
    }
}

?>