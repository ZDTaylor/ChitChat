<?php

require_once "User.php";



//create the Message class

class Message {



public $messageID, $content, $poster, $likes, $dislikes, $mentions, $liked, $disliked;

public function Message(){
$this-> messageID =0;
$this-> content = "";
$this-> poster = 0;
$this-> likes = 0;
$this-> dislikes = 0;
$this-> mentions = array();
$this-> liked = false;
$this-> disliked = false;

}



//Create mentionUser function
function mentionUser(userID){

foreach ($this->mentions as $value){
$found = false;
if (userID == $value){
$found = true;
break;
}
}
if ($found == false) {
array_push($this->mentions, userID);
return true;
}
return false;
}

?>