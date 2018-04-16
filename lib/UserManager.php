<?php


require_once "lib/login.php";
require_once "lib/User.php";


class UserManager {

    private $database;
    private $salt;

    //Default constructor for UserManager class
    function __construct() {
        global $db_login;
        $this->database = new mysqli($db_login["hn"], $db_login["un"], $db_login["pw"], $db_login["db"]);
        if ($this->database->connect_error) die($this->database->connect_error);

        $this->salt = '***REMOVED***';
    }


    //Register function - checks to see if eamail and password are already stored in the database
    function register($email, $password) {

        // Hash the user's password before inserting it into the table.  If it returns false, return false.
        if (!$hashed_password = crypt($password, $this->salt)) {
            return false;
        }

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare("INSERT INTO Users(email, passwd) VALUES (?, ?);")) {
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('ss', $email, $hashed_password)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        else {
            $stmt->close();
            return false;
        }
    }


    //Login function - checks entered email and password against known entries in the database
    function logIn($email, $password) {
        $userID;
        $isAdmin;
        $banned;
        $suspended;

        // Hash the user's password before inserting it into the table.  If it returns false, return false.
        if (!$hashed_password = crypt($password, $this->salt)) {
            return false;
        }

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare("SELECT userID, Users.admin, banned, suspended FROM Users WHERE email = ? and passwd = ?;")) {
            $stmt->close();
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('ss', $email, $hashed_password)) {
            $stmt->close();
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($userID, $isAdmin, $banned, $suspended);
                $stmt->fetch();
                $user = new User();
                $user->userID = $userID;
                $user->email = $email;
                $user->displayName = $this->generateDisplayName($userID);
                $user->isAdmin = $isAdmin;
                $user->banned = $banned;
                $user->suspended = new DateTime($suspended);
                $stmt->close();
                return $user;
            }
            $stmt->close();
            return false;
        }
        else {
            $stmt->close();
            return false;
        }
    }


    function checkBannedSuspended($user) {
        $banned;
        $suspended;

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare("SELECT banned, suspended FROM Users WHERE email = ?;")) {
            $stmt->close();
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('s', $user->email)) {
            $stmt->close();
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($banned, $suspended);
                $stmt->fetch();
                $user->banned = $banned;
                $user->suspended = new DateTime($suspended);
                $stmt->close();
                return $user;
            }
            $stmt->close();
            return false;
        }
        else {
            $stmt->close();
            return false;
        }
    }


    //Delete function - deletes the users account from the database
    function delete($userID){


        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare("Update Users SET email = null, passwd = null WHERE userID = ?;")) {
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('s', $userID)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        else {
            $stmt->close();
            return false;
        }
    }


    //Resetpasswordsendemail - creates a unique string and stores it in the database - sends the user an email
    function resetPasswordSendEmail($email){
    }


    //Resetpasswordreset - takes the key created in resetpasswordsendemail and the user's new password to update password
    function resetPasswordReset($key, $newPassword){
    }


    //Suspend function - suspends a user account if the logged in user is admin
    function suspend($userToSuspend, $suspendTime){
		if (!$stmt = $this->database->prepare("Update Users SET suspended = ? WHERE userID = ?;")) {
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('s s', $suspendTime, $userToSuspend)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        else {
            $stmt->close();
            return false;
        }

    }


    //Ban function - bans a user's account if the logged in user is admin
    function ban($userToBan){
		if (!$stmt = $this->database->prepare("Update Users SET ban = 1 WHERE userID = ?;")) {
            return false;
        }

        // Bind parameters to the statement for every '?' in the prepared statement.  Must specify type
        // In this case both are strings, so you can use 'ss' or 's s'
        // Type codes can be found here: https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
        // If it returns false, return false.
        if (!$stmt->bind_param('s', $userToBan)) {
            return false;
        }

        // Execute the statement.  This will just run it.
        // If it was successful, return true.  Otherwise return false.
        // ALWAYS close the statement before returning.
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        else {
            $stmt->close();
            return false;
        }

    }


    //Generatedisplayname function - randomly generates a display name for the user
    function generateDisplayName($id){
        $displayName = "Anonymous#" . sprintf("%08d", $id);
        return $displayName;
    }
}
?>