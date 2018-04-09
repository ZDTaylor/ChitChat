<?php


require_once "login.php";
require_once "User.php";


class UserManager {

    private $database;
    private $salt;

    //Default constructor for UserManager class
    function __UserManager(){
        $this->database = mysqli_connect($db_login["hn"], $db_login["un"], $db_login["pw"], $db_login["db"]);
        if ($this->database->connect_error) die($this->database->connect_error);

        $this->salt = '***REMOVED***';
    }

    //Register function - checks to see if eamail and password are already stored in the database
    function register($email, $password){

        // Hash the user's password before inserting it into the table.  If it returns false, return false.
        if (!$hashed_password = password_hash($password, PASSWORD_BCRYPT, ["salt" => $this->salt])) {
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
    function logIn($email, $password){
        $userID;
        $userEmail;

        // Hash the user's password before inserting it into the table.  If it returns false, return false.
        if (!$hashed_password = password_hash($password, PASSWORD_BCRYPT, ["salt" => $this->salt])) {
            return false;
        }

        // Prepare the insert statement.  '?' represents a variable that we will bind later.
        // If it returns false, return false.
        if (!$stmt = $this->database->prepare("SELECT userID, email FROM Users WHERE email = ? and passwd = ?;")) {
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
            $stmt->bind_result($userID, $userEmail);
            $stmt->fetch();
            $user = new User();
            $user->userID = $userID;
            $user->email = $userEmail;
            $user->name = generateDisplayName($userID);
            return $user;
        }
        else {
            $stmt->close();
            return false;
        }
    }



    //Logout function - Logs the user out of the page
    function logOut(){
    }


    //Delete function - deletes the users account from the database
    function delete($userID){

        //check variables for table names
        if (!$del = "DELETE FROM db WHERE un = $userID"){
            return false;
        }
        return true;
    }


    //Resetpasswordsendemail - creates a unique string and stores it in the database - sends the user an email
    function resetPasswordSendEmail($email){
    }


    //Resetpasswordreset - takes the key created in resetpasswordsendemail and the user's new password to update password
    function resetPasswordReset($key, $newPassword){
    }


    //Suspend function - suspends a user account if the logged in user is admin
    function suspend($currentUser, $userToSuspend){
        if($currentUser == "admin"){

        }
    }


    //Ban function - bans a user's account if the logged in user is admin
    function ban($currentUser, $userToBan){
        if($currentUser == "admin"){

        }
    }


    //Generatedisplayname function - randomly generates a display name for the user
    function generateDisplayName($id){
        $displayName = "Anonymous#" . sprintf("%08d", $id);
        return $displayName;
    }
}
?>