<?php

 
require_once "login.php";

require_once "User.php";


class UserManager {





public $database;


//Default constructor for UserManager class
function __UserManager(){
$conn = mysqli_connect($email, $passwd, $name, $admin, $banned, $suspended, $deleted, $resetUrl);
if ($conn->connect_error) die($conn->connect_error);
$query = "SELECT * FROM dcsp03";
$this -> database = $conn->query($query);
}


//Register function - checks to see if eamail and password are already stored in the database
function register($email, $password){
if($email != $_POST["email"] ){
$sql = "INSERT INTO dcsp03 (email, passwd)
VALUES ($email, $password)";
return true;
}
else{
return false;
}
}


//Login function - checks entered email and password against known entries in the database
function logIn($email, $password){
}



//Logout function - Logs the user out of the page
function logOut(){
}


//Delete function - deletes the users account from the database
function delete($userID){
}


//Resetpasswordsendemail - creates a unique string and stores it in the database - sends the user an email
function resetPasswordSendEmail($email){
}


//Resetpasswordreset - takes the key created in resetpasswordsendemail and the user's new password to update password
function resetPasswordReset($key, $newPassword){
}


//Suspend function - suspends a user account if the logged in user is admin
function suspend($currentUser, $userToSuspend){
}


//Ban function - bans a user's account if the logged in user is admin
function ban($currentUser, $userToBan){
}


//Generatedisplayname function - randomly generates a display name for the user
function generateDisplayName(){
}

}
?>