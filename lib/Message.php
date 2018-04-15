<?php

//create the Message class

class Message {

    public $messageID, $content, $poster, $net_likes, $mentions, $reaction;


    public function __construct() {
        $this->messageID = 0;
        $this->content = "";
        $this->poster = 0;
        $this->net_likes = 0;
        $this->mentions = [];
        $this->reaction = 0;
    }
}

?>