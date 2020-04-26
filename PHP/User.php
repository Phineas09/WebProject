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
    private $details = null;

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
            $this->findDetails();
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
        $instance->findDetails();

        return $instance;
    }

    public static function newUser($name, $method, $oauthId, $email, $password = "", $sendEmail = true) {
        
        $instance = new self();
        $instance->user = $instance->createNewUser($_MyPost->name, $method, $oauthId, $_MyPost->email, $_MyPost->password);
        $instance->user->save();
        $instance->guest = false;
        $instance->createPrivileges();
        $instance->createDetails();

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
        $instance->findDetails();
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
                                '<li><a href="#" onclick=\'changePage("ProfilePage"); return false\'>
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

    public function getUserProblemsOptions() {
        if(!$this->isGuest()) {
            return '<button class="project-view-button" onclick=\'changePage("NewProblem"); 
            return false\'><i class="fas fa-plus-circle"></i> New Project</button>'
            . (($this->isAdmin() || $this->canApprove()) ? 
            '<button class="project-view-button" onclick="pendingApproval(); 
            return false"><i class="fas fa-check-circle"></i> View Pending</button>' 
            : 
            '');
        }
        else {
            return '';
        }
    }


    public function getPageContents($pageName) {


        
    }

    public function getUserHash() {
        if(!$this->isGuest()){
            return $this->user->hash;
        }
        return "0000";
    }

    public function updateDetails($_MyPost) {
        if(!$this->isGuest()) {

            $this->updateUserDetails($_MyPost->first_name, $_MyPost->last_name, $_MyPost->address,
            $_MyPost->phone_number, $_MyPost->birth_date);

        }
        else {
            throw new Exception("User is guest!");
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

    public function canApprove() {
        if(!$this->isGuest()){
            return ($this->privileges->cna_approve) ? true : false;
        }
    }



    public function getFirstName() {
        return $this->details->first_name;
    }

    public function getLastName() {
        return $this->details->last_name;
    }

    public function getAddress() {
        return $this->details->address;
    }

    public function getPhone() {
        return $this->details->phone_number;
    }

    public function getProfilePicture() {
        return $this->details->profile_picture;
    }

    public function setProfilePicture(string $path) {
        if(!$this->isGuest()) {
            $this->details->profile_picture = $path;
            $this->details->save();
        }
        return;
    }

    public function getTitle() {
        return $this->details->title;
    }

    public function setTitle(string $title) {
        if(!$this->guest()) {
            $this->details->title = $title;
            $this->details->save();
        }
    }

    public function getBirthDate() {
        return $this->details->birth_date;
    }

    public function markLastAct() {
        if(!$this->isGuest()) {
            ORM::get_db()->exec('UPDATE user_details 
            set lastAct = CURRENT_TIMESTAMP
            where user =' . $this->user->id . ';');
            return;
        }
        throw new Exception("User is a gues!");
    }

    public function getUserPoints() {
        if(!$this->isGuest()) {
            $problemsSolved = ORM::for_table('problems')
                ->inner_join(
                    'problems_solved',
                    array( 'problems_solved.problem', '=', 'problems.id'))
                ->where(array('problems_solved.user' => $this->user->id, 'problems.approved' => 1))   
                ->select_expr('sum(problems.points)', 'result')    
                ->find_many();

            if($problemsSolved) {
                return $problemsSolved[0]->result;
            }
        }
        return 0;
    }

    public function getNumberOfProblemsSolved() {
        if(!$this->isGuest()) {
            $problemsSolved = ORM::for_table('problems')
                ->inner_join(
                    'problems_solved',
                    array( 'problems_solved.problem', '=', 'problems.id'))
                ->where(array('problems_solved.user' => $this->user->id, 'problems.approved' => 1))  
                ->select_expr('count(*)', 'result')    
                ->find_many();

            if($problemsSolved) {
                return $problemsSolved[0]->result;
            }
        }
        return 0;
    }

    public function getUsername() {
        if(!$this->isGuest()) {
            return $this->user->name;
        }
        return "Guest";
    }

    public function getEmailAddress() {
        if(!$this->isGuest()) {
            return $this->user->email;
        }
        return "mtarena@mtarena.ro";
    }

    public function getNumberOfPublishedProblems() {
        if(!$this->isGuest()) {
            $problemsSolved = ORM::for_table('problems')
                ->select_expr('count(*)', 'result')    
                ->where(array('problems.author' => $this->user->id))  
                ->find_many();
            if($problemsSolved) {
                return $problemsSolved[0]->result;
            }
        }
        return 0;
    }

    public function changePassword($currentPassword, $newPassword) {
        if(!$this->isGuest()){

            if(strcmp(hash('sha256', $currentPassword), $this->user->password) == 0) {
                $this->user->password = hash('sha256', $newPassword);
                $this->user->save();
            }
            else {
                throw new Exception("Wrong password!");
            }
            return;
        }
        throw new Exception("User is guest!");
        

    }

    // !Helper functions

    private function updateUserDetails($first_name, $last_name, $address, $phone_number, $birth_date) {
        if(!$this->isGuest()){
            $this->details->first_name = $first_name;
            $this->details->last_name = $last_name;
            $this->details->address = $address;
            $this->details->phone_number = $phone_number;
            $this->details->birth_date = $birth_date;

            try{
                $this->details->save();
            }
            catch(Exception $e) {
                throw new Exception("Database Error!");
            }
        }
    }


    private function findPrivileges() {
        $privileges = ORM::for_table('privileges')->where(array('user' => $this->user->id))->find_one();
        if($privileges == false) {
            return $this->createPrivileges();
        }
        $this->privileges = $privileges;
        return $privileges;
    }

    private function findDetails() {
        $details = ORM::for_table('user_details')->where(array('user' => $this->user->id))->find_one();
        if($details == false) {
            return $this->createDetails();
        }
        $this->details = $details;
        return $details;
    }

    private function createPrivileges() {
        $privileges = ORM::for_table('privileges')->create();
        $privileges->user = $this->user->id;
        $privileges->save();
        $this->privileges = $privileges;
        return $privileges;
    }

    private function createDetails() {
        $details = ORM::for_table('user_details')->create();
        $details->user = $this->user->id;
        $details->save();
        $this->details = $details;
        return $details;
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