<?php

 
require_once "login.php";

require_once "User.php";


class UserManager {





public $database;

function __UserManager(){
$this -> database = mysqli_connect($email, $passwd, $name, $admin, $banned, $suspended, $deleted, $resetUrl);
if ($this->database->connect_error) die($this->database->connect_error);
}

function register($email, $password){
$query = "SELECT * FROM dcsp03 WHERE email= $email";
$result = $this -> database->query($query);
if(email != $result ){
return true;
}
else{
return false;
}
}

function logIn(email, password){
}

function logOut(){
}

function delete(userID){
}

function resetPasswordSendEmail(email){
}

function resetPasswordReset(key, newPassword){
}

function suspend(currentUser, userToSuspend){
}

function ban(currentUser, userToBan){
}

function generateDisplayName(){
}

}
?>