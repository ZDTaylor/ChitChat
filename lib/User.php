<?php

class User {
    public $userID, $displayName, $isAdmin, $email, $suspended, $banned;

    public function __construct() {
        $userID = 0;
        $displayName = "";
        $isAdmin = false;
        $email = "";
        $suspended = '1970-01-01 00:00:00';
        $banned = false;
    }
}

?>