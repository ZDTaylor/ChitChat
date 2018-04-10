<?php

//create the Message class

class Message {

    public $messageID, $content, $poster, $likes, $dislikes, $mentions, $liked, $disliked;


    public function __construct() {
        $this-> messageID = 0;
        $this-> content = "";
        $this-> poster = 0;
        $this-> likes = 0;
        $this-> dislikes = 0;
        $this-> mentions = [];
        $this-> liked = false;
        $this-> disliked = false;
    }
}

?>