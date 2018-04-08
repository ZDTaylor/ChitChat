<?php

require_once "login.php";
require_once "User.php";
require_once "Message.php";

class Messenger {
    private $database;
    private $salt;
    //Default constructor for UserManager class
    function __UserManager(){
        $this->database = mysqli_connect($db_login["hn"], $db_login["un"], $db_login["pw"], $db_login["db"]);
        if ($this->database->connect_error){
            die($this->database->connect_error)
        }
        $this->salt = '***REMOVED***';
    }

    //Load function - The load function will load a set of messages from the database and return an array of Message objects.
    function load(){
        $query = mysql_query("SELECT content FROM Messages");
        $message_array = array();
        while ($row = mysql_fetch_assoc(query)){
            $message_array[] = $row;
        }
    }
}

?>