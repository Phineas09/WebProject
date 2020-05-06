<?php

class Chat {


    private $currentUser = null;
    private $otherUser = null;
    private $messages = null;

    public function __construct($otherUserId) {
        $currentUser = new User();
        $otherUser = User::byId($otherUserId);
    }


    //! Functions here for format and other stuff





}
