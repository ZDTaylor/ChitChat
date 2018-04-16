<?php

class User {
    public $userID, $displayName, $isAdmin, $email, $suspended, $banned;

    public function __construct() {
        $this->userID = 0;
        $this->displayName = "";
        $this->isAdmin = false;
        $this->email = "";
        $this->suspended = '1970-01-01 00:00:00';
        $this->banned = false;
    }
}

?>