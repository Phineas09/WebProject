<?php

/**
 * Returns user to use in context
 * @return User 
 * @throws Exception 
 */


 
class User {

    private $guest = null;
    private $user = null;
    private $privileges = null;

    // ! Ctors

    public function __construct() {
        if (isset($_COOKIE['LoggedUser'])) {
            $user = ORM::for_table('users')
                ->where(array(
                    'hash' => $_COOKIE['LoggedUser']
                ))
                ->find_one();

            if($user == false) {
                $this->guest = true;
                return;
            }
            $this->user = $user;
            $this->guest = false;
            $this->findPrivileges();
        }
        else {
            $this->guest = true;
        }
    }

    public static function byEmail($email) {

        $instance = new self();

        $user = ORM::for_table('users')
        ->where(array(
            'email' => strtolower($_MyPost->email)
        ))
        ->find_one();
        
        if($user == false)
            return $instance;

        $instance->user = $user;
        $instance->guest = false;
        $instance->findPrivileges();

        return $instance;
    }

    public static function newUser($name, $method, $oauthId, $email, $password = "", $sendEmail = true) {
        
        $instance = new self();
        $instance->user = $instance->createNewUser($_MyPost->name, $method, $oauthId, $_MyPost->email, $_MyPost->password);
        $instance->user->save();
        $instance->guest = false;
        $instance->createPrivileges();

        //if($sendEmail === true)
            //! Send verification email ?  

        return $instance;
    }

    public static function matchAccount($email, $password, $oauthId = "", $oauthMethod = "") {

        $instance = new self();
        if($oauthId === "" && $oauthMethod === "") {
            $user = ORM::for_table('users')
                        ->where(array(
                            'email' => strtolower($email),
                            'password' => hash('sha256', $password)
                        ))
                        ->find_one();
        }
        else {
            $user = ORM::for_table('users')
            ->where(array(
                'oauth' => $oauthMethod,
                'oauthId' => $oauthId
            ))
            ->find_one();
        }
        if($user == false) {
            $instance->guest = true;
            return $instance;
        }

        $instance->user = $user;
        $instance->guest = false;
        $instance->findPrivileges();
        $instance->login();
        return $instance;        
    }

    public function isGuest() {
        return ($this->guest == true) ? true : false;
    }

    public function generateSendNewPassword() {
        if(!$this->isGuest()) {
            $newPassword = $this->generateRandomString(15);
            $this->user->password = hash('sha256', $newPassword);
            sendNewPassword($newPassword, $this->user->name, $this->user->email);
            $this->user->save();
        }
    }

    public function setStatusOffline() {
        if(!$this->isGuest()){
            $this->user->online = 0;
            $this->user->save();
        }
    }

    public function setStatusOnline() {
        if(!$this->isGuest()){
            $this->user->online = 1;
            $this->user->save();
        }
    }

    public function validateAccount() {
        if(!$this->isGuest())
        {
            $this->user->validated = 1;
            $this->user->save();
        }
    }

    public function login() {
        if(!$this->isGuest()) {
            $_SESSION["Logged"] = "true";
            setcookie("LoggedUser", $this->user->hash, time()+3600, "/");
            $this->setStatusOnline();
            $this->logUser($this->user->id);
        }
    }

    public static function wipeLoginCookies() {
        unset($_COOKIE['LoggedUser']);
        setcookie('LoggedUser', null, -1, '/');
        //Uset Session variable
        unset($_SESSION["Logged"]);
    }

    public function getUserNavbar() {
        if(!$this->isGuest()) {

            return '<a id="navLogin" href="" onclick="return false">
            <i class="fas fa-user-circle"></i> '  . explode(" ", $this->user->name)[0] . '</a>
            <div class="hidden-navbar-ul">
                            <ul>' 
                            . ($this->isAdmin() ? 
                            '<li><a href="#" onclick="pageChangeProjects(); return false">
                            <i class="fas fa-lock"></i> Admin</a></li>' 
                                : '') .
                                '<li><a href="#" onclick=" return false">
                                <i class="fas fa-address-card"></i> Profile</a></li>	

                                <li><a href="#" onclick=\'logOut("false"); return false\'>
                                <i class="fas fa-sign-out-alt"></i> LogOut</a></li>
							</ul>	
                        </div>
            ';
        }
        else {
            return '<a id="navLogin" href="" onclick="popUpLogin(); return false">Login</a>';
        }

    }

    public function getOauth() {
        if(!$this->isGuest()) {
            return $this->user->oauth;
        }


    }
    public function isAdmin() {
        if(!$this->isGuest()){
            return ($this->privileges->is_admin) ? true : false;
        }
    }


    // !Helper functions


    private function findPrivileges() {
        $privileges = ORM::for_table('privileges')->where(array('user' => $this->user->id))->find_one();
        $this->privileges = $privileges;
        return $privileges;
    }

    private function createPrivileges() {
        $privileges = ORM::for_table('privileges')->create();
        $privileges->user = $this->user->id;
        $privileges->save();
        $this->privileges = $privileges;
        return $privileges;
    }

    private function logUser($userId) {
        $log = ORM::for_table('logs')->create();
        $log->user = $userId;
        $log->save();
    }

    private function createNewUser($name, $method, $id, $email, $password) {
        $user = ORM::for_table('users')->create();
        $user->name = $name;
        $user->oauth = $method;
        $user->oauthId = $id;
        $user->email = strtolower($email);
        $user->password = hash('sha256', $password);
        $user->hash = hash('md5', $method . $name . $email);
        $user->validated = 0;
        $user->online = 0;
        return $user;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}