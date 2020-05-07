<?php

require_once("./idiorm.php");


class Chat {


    private $currentUser = null;
    private $otherUser = null;
    private $messages = null;

    public function __construct() {
        $this->currentUser = new User();
        $this->otherUser = null;
    }


    public static function getInstance($userId) {

        $instance = new self();
        $instance->currentUser = new User();
        $instance->otherUser = User::byId($userId);
        $instance->_getMessages();
        return $instance;
    }


    public static function pushMessage($userId, $message) {

        $user = new User();
        if(!$user->isGuest()) {

            $newMessage = ORM::for_table("user_messages")->create();
            $newMessage->message = $message;
            $newMessage->user = $user->getId();
            $newMessage->to = $userId;
            $newMessage->save();

            $markNewMessage = ORM::for_table("user_friends")
            ->where(array("user" => $userId, "friend" => $user->getId()))
            ->find_one();

            $markNewMessage->newMessage = 1;
            $markNewMessage->save();

            return;
        }
        throw new Exception ("Unexpected error!");

    }

    //! Functions here for format and other stuff

    public function getFriendsFormatted() {

        if(!$this->currentUser->isGuest()) {

            $friendList = $this->_getUserFriends();

            $response = "";

            foreach($friendList as $friend) {
                $response = $response . $this->_getChatElement($friend);
            }
            return $response;
        }
        throw new Exception("User is guest!");

    }

    public function getMessagesFormatted() {
        if($this->messages) {

            /*
            <div class="message message-left">
				<div class="profileImageSmall">
					<img src="/Misc/ProfilePictures/1807639c0ae5a986c0e076b543e2251b" alt="avatar" />
				</div>
				<div class="bubble bubble-light">Hey anhat!</div>
			</div>
			<div class="message message-right">
				<div class="profileImageSmall">
					<img src="/Misc/Default/Profile.png" alt="avatar" />
				</div>
				<div class="bubble bubble-dark">what is going on?</div>
				<div class="bubble bubble-dark">what is going on?</div>
				<div class="bubble bubble-dark">what is going on?</div>
			</div>

            */

            $me = $this->currentUser->getId();
            $other = $this->otherUser->getId();

            $response = "";

            $last = null;

            foreach($this->messages as &$message) {

                if(strcmp($message->user, $me) == 0) {
                    if($last && strcmp($last, $me) == 0) {
                        $response = $response . '<div class="bubble bubble-dark">' . $message->message . '</div>';
                    } 
                    else {
                        if($last) {
                            $response = $response . '</div>';
                        }
                        $response = $response . 
                        '<div class="message message-right">
                            <div class="profileImageSmall">
                                <img src="' . $this->currentUser->getProfilePicture() . '" alt="avatar" />
                            </div>
                            <div class="bubble bubble-dark">' . $message->message . '</div>';
                    }
                } else {
                    $message->read = 1;
                    $message->save();
                    if($last && strcmp($last, $other) == 0) {
                        $response = $response . '<div class="bubble bubble-light">' . $message->message . '</div>';
                    } 
                    else {
                        if($last) {
                            $response = $response . '</div>';
                        }
                        $response = $response . 
                        '<div class="message message-left">
                            <div class="profileImageSmall">
                                <img src="' . $this->otherUser->getProfilePicture() . '" alt="avatar" />
                            </div>
                            <div class="bubble bubble-light">' . $message->message . '</div>';
                    }
                }
        
                $last = $message->user;
            }
            return $response;

        }
        return "";
    }


    public function pullMessagesFormatted() {

        $me = $this->currentUser->getId();
        $other = $this->otherUser->getId();

        $response = "";

        $last = null;

        foreach($this->messages as &$message) {
            if(strcmp($message->to, $me) == 0 && intval($message->read) == 0) {
                if(strcmp($message->user, $me) == 0) {
                    if($last && strcmp($last, $me) == 0) {
                        $response = $response . '<div class="bubble bubble-dark">' . $message->message . '</div>';
                    } 
                    else {
                        if($last) {
                            $response = $response . '</div>';
                        }
                        $response = $response . 
                        '<div class="message message-right">
                            <div class="profileImageSmall">
                                <img src="' . $this->currentUser->getProfilePicture() . '" alt="avatar" />
                            </div>
                            <div class="bubble bubble-dark">' . $message->message . '</div>';
                    }
                } else {
                    $message->read = 1;
                    $message->save();
                    if($last && strcmp($last, $other) == 0) {
                        $response = $response . '<div class="bubble bubble-light">' . $message->message . '</div>';
                    } 
                    else {
                        if($last) {
                            $response = $response . '</div>';
                        }
                        $response = $response . 
                        '<div class="message message-left">
                            <div class="profileImageSmall">
                                <img src="' . $this->otherUser->getProfilePicture() . '" alt="avatar" />
                            </div>
                            <div class="bubble bubble-light">' . $message->message . '</div>';
                    }
                }
                $last = $message->user;
            }
        }
        return $response;
    }

    //! Private functions <here!@ id="!"></here!@>

    private function _getUserFriends() {
        
        $results = ORM::for_table("user_friends")
        ->inner_join(
            'users',
            array( 'users.id', '=', 'user_friends.friend'))
        ->inner_join(
            'user_details',
            array( 'user_details.user', '=', 'user_friends.friend'))
        ->where(array('user_friends.user' => $this->currentUser->getId(), 'user_friends.accepted' => 1))
        ->select_many(
            array('id' => 'user_friends.friend'),
            array('userName' => 'users.name'),
            array('status' => 'users.online'),
            array('picture' => 'user_details.profile_picture'))        
        ->find_many();

        if($results) {
            return $results;
        }
        throw new Exception("Something internal happened!");
    }


    private function _getMessages() {


        $userId = $this->currentUser->getId();
        $toId = $this->otherUser->getId();
        
        $results = ORM::for_table("user_messages")
            ->where_raw('(`user` = ? AND `to` = ?) OR (`to` = ? AND `user`= ?)', array($userId, $toId, $userId, $toId))
            ->order_by_asc('date')       
            ->find_many();
        
        $this->messages = $results;
        return $results;
    }

    private function _getChatElement($friend) {
    /*
        <div onclick="openChatMessage(this)" class="chatContact">
            <img src="/Misc/ProfilePictures/1807639c0ae5a986c0e076b543e2251b" alt="avatar" />
            <div class="hiddenChatId hidden">ID</div>
            <div class="about">
                <div class="name">Vincent Porter</div>
                <div class="status">
                    <i class="fa fa-circle online"></i> online
                </div>
            </div>
        </div>

    */
        $status = (intval($friend->status) == 0) ? "offline" : "online";

        $_toReturn = '<div onclick="openChatMessage(this)" class="chatContact">
                        <img src="' . $friend->picture . '" alt="avatar" />
                        <div class="hiddenChatId hidden">' . $friend->id . '</div>
                        <div class="about">
                            <div class="name">' . $friend->userName . '</div>
                            <div class="status">
                                <i class="fa fa-circle ' . $status . '"></i> ' . $status . '
                            </div>
                        </div>
                    </div>';

        return $_toReturn;
    }

}
