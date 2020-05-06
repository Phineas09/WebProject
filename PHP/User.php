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
    private $notifications = null;

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
            $this->findNotifications();
        }
        else {
            $this->guest = true;
        }
    }

    public static function byEmail($email) {

        $instance = new self();

        $user = ORM::for_table('users')
        ->where(array(
            'email' => strtolower($email)
        ))
        ->find_one();
        
        if($user == false)
            return $instance;

        $instance->user = $user;
        $instance->guest = false;
        $instance->findPrivileges();
        $instance->findDetails();
        $instance->findNotifications();
        return $instance;
    }

    public static function byId($id) {

        $instance = new self();

        $user = ORM::for_table('users')
        ->where(array(
            'id' => strtolower($id)
        ))
        ->find_one();
        
        if($user == false)
            return $instance;

        $instance->user = $user;
        $instance->guest = false;
        $instance->findPrivileges();
        $instance->findDetails();
        $instance->findNotifications();

        return $instance;
    }

    public static function newUser($name, $method, $oauthId, $email, $password = "", $sendEmail = true) {
        
        $instance = new self();
        $instance->user = $instance->createNewUser($name, $method, $oauthId, $email, $password);
        $instance->user->save();
        $instance->guest = false;
        $instance->createPrivileges();
        $instance->createDetails();
        $instance->createNotifications();

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
        $instance->findNotifications();

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
                            '<li><a href="#" onclick=\'changePage("AdminStatistics"); return false\'>
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
            throw new Exception("User is guest!");
            //return '<a id="navLogin" href="" onclick="popUpLogin(); return false">Login</a>';
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

    public function changeTitle(string $newTitle) {
        if(!$this->isGuest()) {
            $this->details->title = $newTitle;
            $this->details->save();
        }
        return;
    }

    public function adminUpdateDetails($_MyPost) {
        if(!$this->isGuest()) {

            $this->updateUserDetails($_MyPost->first_name, $_MyPost->last_name, $this->details->address,
            $_MyPost->phone_number, $this->details->birth_date);

            if(strcmp($_MyPost->password, "Unset") != 0) {
                $this->adminChangePassword($_MyPost->password);
            }
            $this->changeTitle($_MyPost->title);

            $this->updatePrivileges($_MyPost->is_admin, $_MyPost->can_modify, $_MyPost->can_approve);
            
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
        return false;
    }

    public function canApprove() {
        if(!$this->isGuest()){
            return ($this->privileges->can_approve) ? true : false;
        }
        return false;
    }

    public function canModify() {
        if(!$this->isGuest()) {
            return ($this->privileges->can_modify) ? true : false;
        }
        return false;
    }

    public function getId() {
        if(!$this->isGuest()) {
            return $this->user->id;
        }
        return -1;
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

    public function getPrivIsAdmin() {
        if($this->isAdmin()) {
            return "True";
        }
        else {
            return "False";
        }
    }

    public function getPrivCanApprove() {
        if($this->canApprove()) {
            return "True";
        }
        else {
            return "False";
        }
    }

    public function getPrivCanModify() {
        if($this->canModify()) {
            return "True";
        }
        else {
            return "False";
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
                ->select_expr('sum(problems_solved.points)', 'result')    
                ->find_many();

            if($problemsSolved) {
                return ($problemsSolved[0]->result) ? $problemsSolved[0]->result : 0;
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


    public function ownsProblem($problemId) {

        $result = ORM::for_table("problems")->where(array("id" => $problemId))->find_one();

        if($result) {
            if($result->author == $this->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getUserProblemView($problemId) {

        $problemApproved = $this->isApproved($problemId);

        $viewProblemUserButtons = (!$this->isGuest() && ( $this->isAdmin() || $this->canModify() || $this->ownsProblem($problemId))) ? 
        '   <button id="viewProblemModify" onclick="editProblem(this); return false" class="submitNewProblemButton" >Modify</button>
            <button id="viewProblemCancel" onclick="cancelEdit(); return false" class="submitNewProblemButton hidden" >Cancel</button>' : "";


        $viewProblemUser = ($problemApproved && !$this->isGuest()) ? 
        '<div id="projectViewPrev" class="project-header project-header-formatted">
            <div class="content-container">
                <section class="">
                    <p id="infoMessageSubmit"></p>
                </section>
                <section>
                    <p>*Upload your solution!</p>
                    <form class="newProblemUpload" action="/action_page.php">
                        <label for="files">Source File:</label>
                        <input type="file" id="sourceFile" name="sourceFile"><br><br>
                    </form> 
                </section>
            </div> 
        </div>

        <div class="content-container">
            <button id="submitSolution" onclick="submitSolution(); return false" class="submitNewProblemButton" >Submit Solution</button>
        </div>' : ( ($problemApproved) ? 
        '<div id="projectViewEdit" class="project-header project-header-formatted">
            <div class="content-container">
                <section class="isInfo">
                    <p id="infomessage">You must be a member in order to submit your solution!</p>
                </section>
            </div> 
        </div>' : '' );

        $viewProblemUser = (!$this->isGuest() && ( $this->isAdmin() || $this->canModify() || $this->ownsProblem($problemId))) ? 
        ( $viewProblemUser . '<div id="projectViewEdit" class="project-header project-header-formatted hidden">
            <div class="content-container">
                <section class="">
                    <p id="infoMessageModify"></p>
                </section>
                <section>
                    <p>Input Files</p>
                    <form class="newProblemUpload" action="/action_page.php">
                        <label><a class="downloadInputFiles" onclick="downloadProblemFiles(event)" >Download Input Files</a></label>

                        <label for="testFiles">New Input Files:</label>
                        <input type="file" id="testFiles" name="testFiles" multiple><br><br>

                        <label for="sourceFile">New Source File</label>
                        <input type="file" id="editSourceFile" name="sourceFile"><br><br>
                                        
                        <label for="appendToExisting">Overwrite existing ?</label><br>
                        <input type="checkbox" id="appendToExisting" name="appendToExisting" value=""><br><br>
                    </form> 
                </section>
            </div> 
        </div>

        <div class="content-container hidden">
            <button id="modifyProblem" onclick="submitModifyProblem(); return false" class="submitNewProblemButton" >Commit Edits</button>
        </div>') : $viewProblemUser;



        // If problem is not approved display approve button if is admin and can approve

        if(!$this->isApproved($problemId)) { 
            $viewProblemUser = (!$this->isGuest() && ( $this->isAdmin() || $this->canApprove())) ? 
            ( $viewProblemUser . '

            <div id="projectApproveDetails" class="project-header project-header-formatted">
                <div class="content-container">
                    <section class="">
                        <p id="infoMessageApprove"></p>
                    </section>
                    <section>
                        <p>Approve Problem Details</p>
                        <form class="newProblemUpload" action="/action_page.php">
                            <label><a class="downloadInputFiles" onclick="downloadProblemData(event)" >Download Problem Files</a></label>

                            <label for="sourceFile">Problem Points</label>
                            <input type="number" id="approveProblemPoints" name="sourceFile"><br><br>

                            <label for="difficulty">Difficulty:</label>
                            <div class="approveSelectWrap">
                                <select id="approveProblemDiff">
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                            <label for="textarea">Briefly describe the problem.</label>
                            <textarea id="approveBrief" class="approveTextArea">
                            </textarea>

                        </form> 
                    </section>
                </div> 
            </div>


            <div class="content-container">
                <button id="approveProblem" onclick="approveProblem(); return false" class="submitNewProblemButton" >Approve Problem</button>
            </div>
            ') : $viewProblemUser;
        }

        //$viewProblemUser




        $_returnValue = array (
            "viewProblemUserButtons" => $viewProblemUserButtons,
            "viewProblemUser" => $viewProblemUser
        );

        return $_returnValue;
    }

    public function markNotificationAsRead() {
        try{
            if($this->notifications->data != null) {
                if($this->notifications->new == 0) {

                    $notifications = json_decode(stripslashes($this->notifications->data), true);

                    foreach ($notifications as &$notification) {
                        $notification["read"] = true;
                    } 

                    $this->notifications->data = json_encode($notifications);
                    $this->notifications->save();
                }
            }
        }
        catch(Exception $e) {
            throw new Exception("Something happened!");
        }
    }

    public function getNotificationsAll() {
        if($this->notifications->data != null) {
            return $this->notifications->data;
        }
        return "";
    }

    public function getNotifications() {

        //echo $currentNotifications;
        if($this->notifications) {
            if($this->notifications->data != null && intval($this->notifications->new) == 1) {

                $this->notifications->new = 0;
                $this->notifications->save();

                return array ( 
                    'notifications' => $this->notifications->data,
                    'newNotifications' => '1'
                );
            }
        }
        return array ( 
            'notifications' => '',
            'newNotifications' => '0'
        );
    }

    /**
     * Required usedId
     * @throws Exception
     * @return nothing
     */

    static function deleteUser($userId, int $targetUser) {
        try {
            ORM::get_db()->beginTransaction();
            //Delete logs
            $db = ORM::get_db();
            $db->exec('
                DELETE FROM logs 
                    where user = ' . $targetUser . '
            ;');

            //Delete privileges
            $db->exec('
                DELETE FROM privileges 
                    where user = ' . $targetUser . '
            ;');

            //Delete details

            $db->exec('
                DELETE FROM user_details 
                    where user = ' . $targetUser . '
            ;');

            //Change problems posted ownership to the user that started the deletion

            $db->exec('
                UPDATE problems
                    set author = ' . $userId . '
                    where author = ' . $targetUser . '
            ;');

            //Change problems approved

            $db->exec('
                UPDATE problems_approvedby
                    set user = ' . $userId . '
                    where user = ' . $targetUser . '
            ;');

            //Delte problems_solved

            $db->exec('
                DELETE FROM problems_solved 
                    where user = ' . $targetUser . '
            ;');

            //Delete user entry

            $db->exec('
                DELETE FROM users
                    where id = ' . $targetUser . '
            ;');         
            ORM::get_db()->commit();

        }
        catch(Exception $except) {
            ORM::get_db()->rollBack();
            throw new Expcetion("Could not delete!");
        }
    }



    // !Helper functions

    private function isApproved($problemId) {

        $problem = ORM::for_table("problems")->where(array("id" => $problemId))->find_one();
        if($problem) {
            if($problem->approved == 1)
                return true;
        }
        return false;
    }

    private function updatePrivileges($isAdmin, $canModify, $canApprove) {

        if(strcmp($isAdmin, "False") == 0) {
            $this->privileges->is_admin = 0;
        }
        else {
            if(strcmp($isAdmin, "True") == 0)
                $this->privileges->is_admin = 1;
        }

        if(strcmp($canModify, "False") == 0) {
            $this->privileges->can_modify = 0;
        }
        else {
            if(strcmp($isAdmin, "True") == 0)
                $this->privileges->can_modify = 1;
        }

        if(strcmp($canApprove, "False") == 0) {
            $this->privileges->can_approve = 0;
        }
        else {
            if(strcmp($canApprove, "True") == 0)
                $this->privileges->can_approve = 1;
        }

        $this->privileges->save();
    }

    private function adminChangePassword(string $newPassword) {
        if(!$this->isGuest()){
            $this->user->password = hash('sha256', $newPassword);
            $this->user->save();
            return;
        }
        throw new Exception("User is guest!");
    }


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

    private function findNotifications() {
        $details = ORM::for_table('user_notifications')->where(array('user' => $this->user->id))->find_one();
        if($details == false) {
            return $this->createNotifications();
        }
        $this->notifications = $details;
        return $details;
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

    private function createNotifications() {
        $details = ORM::for_table('user_notifications')->create();
        $details->user = $this->user->id;
        $details->save();
        $this->notifications = $details;
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