<?php

class User {
    public $userID;
    public $displayName;
    public $isAdmin;
    public $email;
    public $password;
    public $suspended;
    public $banned;

    public function User(){
        $this -> userID = 0;
        $this -> displayName = "";        
        $this -> isAdmin = false;
        $this -> email = "";
        $this -> password = "";
        $this -> suspended = "";
        $this -> banned = false;
    }
}

?>