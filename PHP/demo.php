<?php

    //Always return json with statusCode variable set @!#$@!

    require_once("./idiorm.php");
    require_once("./smtp.php");
    require_once("./Logger.php");
    require_once("./PageChanger.php");
    require_once("./ProblemsManager.php");
    require_once('./User.php');
    require_once('./Render.php');
    require_once('./FileUploader.php');
    require_once('./Chat.php');

	ORM::configure('mysql:host=localhost;dbname=mtarena');
	ORM::configure('username', 'root');
	ORM::configure('password', '');

    if(session_id() == '') {
        session_start();
    }

    $problemManager = new ProblemsManager();

    $db = ORM::get_db();


/*
    where_raw
$people = ORM::for_table('person')
            ->where('name', 'Fred')
            ->where_raw('(`age` = ? OR `age` = ?)', array(20, 25))
            ->order_by_asc('name')
            ->find_many();
*/


    $users = "
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER AUTO_INCREMENT PRIMARY KEY,
        oauth TEXT,
        oauthId TEXT, 
        name TEXT NOT NULL, 
        email TEXT NOT NULL,
        password TEXT,
        hash TEXT,
        online INTEGER,
        validated INTEGER
    );";

    $problems = "
        CREATE TABLE IF NOT EXISTS problems (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            author INTEGER,
            approved INTEGER DEFAULT 0,
            name TEXT NOT NULL,
            language TEXT NOT NULL,
            points INTEGER,
            difficulty TEXT DEFAULT 'easy',
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            presentation TEXT,
            testCases INTEGER DEFAULT 0,
            CONSTRAINT fk_author_id FOREIGN KEY (author) REFERENCES users(id)
    );";

    $problems_solved = "
        CREATE TABLE IF NOT EXISTS problems_solved (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            problem INTEGER NOT NULL,
            points FLOAT NOT NULL,
            result TEXT NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_user_id FOREIGN KEY (user) REFERENCES users(id),
            CONSTRAINT fk_problem_id FOREIGN KEY (problem) REFERENCES problems(id)
    );";   
    
    $problems_pending = "
        CREATE TABLE IF NOT EXISTS problems_pending (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            problem INTEGER NOT NULL,
            CONSTRAINT fk_pending_id FOREIGN KEY (problem) REFERENCES problems(id)
    );";

    $problems_approvedBy = "
        CREATE TABLE IF NOT EXISTS problems_approvedBy (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            problem INTEGER NOT NULL,
            CONSTRAINT fk_approvedBy_id FOREIGN KEY (user) REFERENCES users(id),
            CONSTRAINT fk_problemApproved_id FOREIGN KEY (problem) REFERENCES problems(id) 
    );";

    $logs = "
        CREATE TABLE IF NOT EXISTS logs (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT FK_USER_LOGS_ID FOREIGN KEY (user) REFERENCES users(id)
    );";

    $logs_view = "
        CREATE OR REPLACE VIEW userStatistics AS 
        SELECT 
        IF(user IS NULL, 0, user) AS user,
        b.Days AS date
    FROM 
        (SELECT a.Days 
        FROM (
            SELECT curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS Days
            FROM       (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
            CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
            CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
        ) a
        WHERE a.Days >= curdate() - INTERVAL 13 DAY) b
    LEFT JOIN (SELECT
                DATE(date) as day,
                COUNT(DISTINCT(user)) AS user
            FROM
                logs
            GROUP BY
                DATE(date)) s
        ON s.day = b.Days
    ORDER BY b.Days;
    ;";  

    $privilege = "
        CREATE TABLE IF NOT EXISTS privileges (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            is_admin BOOLEAN DEFAULT false,
            can_modify BOOLEAN DEFAULT false,
            can_approve BOOLEAN DEFAULT false,
            CONSTRAINT fk_user_privileges FOREIGN KEY (user) REFERENCES users(id)
    );";

    $user_details = "
        CREATE TABLE IF NOT EXISTS user_details (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            first_name TEXT DEFAULT 'Unset',
            last_name TEXT DEFAULT 'Unset',
            address TEXT DEFAULT 'Unset',
            phone_number TEXT DEFAULT 'Unset',
            birth_date DATE,
            profile_picture TEXT DEFAULT '/Misc/Default/Profile.png',
            title TEXT DEFAULT 'Rookie',
            lastAct TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_user_details FOREIGN KEY (user) REFERENCES users(id)
    );";

    $eventUsersOnline = '
        CREATE EVENT usersOnline
            ON SCHEDULE
                EVERY 60 SECOND
            DO
                UPDATE users u
                INNER JOIN user_details ud ON u.id = ud.user
                SET u.online = if(NOW() - ud.lastAct < 60 , 1, 0);
    ';

    $userNotifications = '
        CREATE TABLE IF NOT EXISTS user_notifications (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER not null,
            data TEXT,
            new INTEGER DEFAULT 0,
            CONSTRAINT fk_notifications_user FOREIGN KEY (user) REFERENCES users(id)
        );
    ';


    $userFriends = '
        CREATE TABLE IF NOT EXISTS user_friends (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER not null,
            friend INTEGER not null,
            accepted INTEGER DEFAULT 0,
            newMessage INTEGER DEFAULT 0,
            CONSTRAINT fk_user_friends FOREIGN KEY (user) REFERENCES users(id),
            CONSTRAINT fk_user_friends_friend FOREIGN KEY (friend) REFERENCES users(id)
        );
    ';

    $userMessages = '
        CREATE TABLE IF NOT EXISTS user_messages (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            `from` INTEGER not null,
            `to` INTEGER not null,
            message TEXT not null,
            `read` INTEGER DEFAULT 0,
            `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_user_messages_from FOREIGN KEY (`from`) REFERENCES users(id),
            CONSTRAINT fk_user_messages_to FOREIGN KEY (to) REFERENCES users(id)
        );  
    ';


    //! Message table incoming

    $db->exec($users);
    $db->exec($problems);
    $db->exec($problems_solved);
    //$db->exec($problems_pending);
    $db->exec($problems_approvedBy);
    $db->exec($logs);
    $db->exec($logs_view);
    $db->exec($privilege);
    $db->exec($user_details);
    $db->exec('SET GLOBAL event_scheduler="ON";');
    $db->exec($userNotifications);
    $db->exec($userFriends);
    //$db->exec($eventUsersOnline);

    header("Content-Type: application/json");

//! Work in progress

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['files']) && (isset($_POST["profileImage"]))) {

            $path = './../Misc/ProfilePictures/';
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];

            $user = new User();

            $all_files = count($_FILES['files']['tmp_name']);

            $file_name = $user->getUserHash();
            $file_tmp = $_FILES['files']['tmp_name'][0];
            $file_type = $_FILES['files']['type'][0];
            $file_size = $_FILES['files']['size'][0];
            $file_ext = pathinfo($_FILES['files']['name'][0], PATHINFO_EXTENSION);

            $file = $path . $file_name; 

            move_uploaded_file($file_tmp, $file);
            $user->setProfilePicture($file);

            echo json_encode(
                array(
                    'statusCode' => 200
                ));

            exit;
        }

        if ((isset($_POST["submitProblem"]))) {
            FileUploader::submitProblem($problemManager);

            //run program compile make output and delete exe
        }

        if(isset($_POST["problemsManager"]) && isset($_POST["modifyProblem"])) {

            try {
                if(isset($_FILES['testFiles'])) {
                    FileUploader::appendFilesToProblem();
                    //Mark as unapproved
                } 
                else {
                    if(isset($_FILES['sourceFile'])) {
                        FileUploader::appendSourceFileToProblem();
                        //Mark as unapproved
                    }
                    FileUploader::updateProblemFormData();  // Change Name   
                }

                $problemPath = '\Misc\Problems\\';
                $problem = ORM::for_table("problems")->where(array("id" => $_POST["problemId"]))->find_one();
                $problemPath = $problemPath . $problem->id . "\\";

                $problemManager->generateOutputFilesCpp($problemPath);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }
            catch (Exception $e) {
                echo json_encode(
                    array(
                        'statusCode' => 420
                    ));
            }
            exit;
        }

        if(isset($_POST["problemsManager"]) && isset($_POST["submitSolution"])) {
            try {
                FileUploader::submitSolution($problemManager);
                exit;
            }
            catch (Exception $e) {
                echo json_encode(
                    array(
                        'statusCode' => 420,
                        'message' => $e->getMessage()
                    ));
            }
            exit;
        }

    }

//!

    $_MyPost = json_decode(stripslashes(file_get_contents("php://input")));
    if(is_null($_MyPost)) {
        header("Status: 404 Not Found");
        echo json_encode(
            array(
                'No data was sent' => 404
            ));
    }
    // Handle POST submission

    if(isset($_MyPost->logger)) {
        $logger = new Logger();
        return $logger->handler($_MyPost);
    }


    if(isset($_MyPost->activeCheck)) {

        try {
            
            $user = new User();

            if(isset($_MyPost->markNotificationsRead)) {

                $user->markNotificationAsRead();

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
                exit;
            }

            if(isset($_MyPost->fetchNotificationsAll)) {

                $notifications = $user->getNotificationsAll();

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'notifications' => $notifications,
                    ));
                exit;
            }

            if(isset($_MyPost->fetchNotifications)) {

                $notifications = $user->getNotifications();
                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'notifications' => $notifications["notifications"],
                        'newNotifications' => $notifications["newNotifications"]
                    ));
                exit;
            }

            $user->markLastAct();
            //Get notifications
            echo json_encode(
                array(
                    'statusCode' => 200
                ));
        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 420
                ));
        }
        exit;

    }

    if(isset($_MyPost->problemsManager)) {
        try {

            if(isset($_MyPost->approveProblem)) {

                $user = new User();
                $problemManager->approveProblem($user, $_MyPost);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
                exit;
            }

            if(isset($_MyPost->downloadInputArhive)) {

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'arhivePath' => FileUploader::makeArhiveFile($_MyPost->problemId)
                    ));

                exit;
            }

            if(isset($_MyPost->downloadProblemArhive)) {

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'arhivePath' => FileUploader::makeArhiveProblem($_MyPost->problemId)
                    ));

                exit;
            }

            echo json_encode(
                array(
                    'statusCode' => 200,
                    'problemData' => $problemManager->getProblemData($_MyPost->problemId),
                    'problemName' => $problemManager->getProblemName($_MyPost->problemId)
                ));
        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 413,
                    'Caught exception: ' => $e->getMessage()
                ));
        }

        exit;

    }


    if(isset($_MyPost->loadProblems)) {
        try {

            if(isset($_MyPost->loadPenginApproval)) {
                $var = $problemManager->getProblemsContentsPendingApproval();
            }
            else {
                if(isset($_MyPost->patternSearch)) {
                    if($_MyPost->pattern === ""){
                        $var = $problemManager->getProblemsPageContents($_MyPost->sortBy, $_MyPost->order);
                    }
                    else {
                        $var = $problemManager->getProblemsPageContentsSearchBy($_MyPost->pattern, $_MyPost->sortBy, $_MyPost->order);
                    }
                }
                else { 
                    $var = $problemManager->getProblemsPageContents($_MyPost->sortBy, $_MyPost->order);
                }
            }
            echo json_encode(
                array(
                    'statusCode' => 200,
                    'problemList' => $var
                ));
        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 412,
                    'Caught exception: ' => $e->getMessage()
                ));
        }

        exit;

    }

    if(isset($_MyPost->pageChange)) {
        try {

            if(isset($_MyPost->userFormatted)) {
                if(isset($_MyPost->ProfilePageAnotherUser)) {
                    $targetUser = User::byId($_MyPost->userId);
                    $pageContent = Render::renderPageContents($targetUser, $_MyPost->page);
                }
                else {
                    $currentUser = new User();
                    $pageContent = Render::renderPageContents($currentUser, $_MyPost->page);
                }
            }
            else {
                $pageContent = PageChanger::getPageContents($_MyPost->page);
            }
            echo json_encode(
                array(
                    'statusCode' => 200,
                    'pageContent' => $pageContent
                ));
        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 404,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
        exit;
    }

    if(isset($_MyPost->user)) {
        try {
        if(isset($_MyPost->loginBar)) {
            try {
                $user = new User();
                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'navbar' => $user->getUserNavbar()
                    ));
                }
                catch (Exception $e) {
                    echo json_encode(
                        array(
                            'statusCode' => 210,
                            'navbar' => '<a id="navLogin" href="" onclick="popUpLogin(); return false">Login</a>'
                        ));
                }
        } 
        if(isset($_MyPost->problemsOptions)) {
            $user = new User();
            echo json_encode(
                array(
                    'statusCode' => 200,
                    'options' => $user->getUserProblemsOptions()
                ));
        }
        if(isset($_MyPost->changeUserDetails)) {
            $user = new User();
            $user->updateDetails($_MyPost);
            echo json_encode(
                array(
                    'statusCode' => 200
                ));
        }
        if(isset($_MyPost->changeUserPassword)) {
            $user = new User();
            $user->changePassword($_MyPost->current_password, $_MyPost->new_password);
            echo json_encode(
                array(
                    'statusCode' => 200
                ));
        }

        if(isset($_MyPost->getUserProblemView)) {

            $user = new User();
            $array = $user->getUserProblemView($_MyPost->problemId);

            echo json_encode(
                array(
                    'statusCode' => 200,
                    "viewProblemUserButtons" => $array["viewProblemUserButtons"],
                    "viewProblemUser" => $array["viewProblemUser"]
                ));
        }

        }
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 415,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
        exit;
    }


    //! Render elements ?

    if(isset($_MyPost->renderElement)) {
        try {
            $element = Render::renderElement($_MyPost->element);
            echo json_encode(
                array(
                    'statusCode' => 200,
                    'elementInner' => $element
                ));
        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 404,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
        exit;
    }


    //? Chat part here!#!@

    if(isset($_MyPost->chat)) {
        try {

            if(isset($_MyPost->getFriendsInfoFormatted)) {

                $chat = new Chat();

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'friendList' => $chat->getFriendsFormatted()
                    ));

                exit;
            }

            if(isset($_MyPost->pushMessage)) {

                Chat::pushMessage($_MyPost->userId, $_MyPost->message);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));

                exit;
            }

            if(isset($_MyPost->getMessagesUser)) {

                $chat = Chat::getInstance($_MyPost->userId);

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'messages' => $chat->getMessagesFormatted()
                    ));

                exit;
            }

            if(isset($_MyPost->messagesOnline)) {

                $user = new User();

                //Get online users
                $result = ORM::for_table('users')
                    ->inner_join('user_friends', array( 'user_friends.friend', '=', 'users.id'))
                    ->where(array('users.online' => 1, 'user_friends.user' => $user->getId() ))
                    ->find_many();

                $onlineUsers = "";
                if($result) {
                    foreach($result as $users) {
                        $onlineUsers = $onlineUsers . $users->friend . ", ";
                    }
                }


                $newMessagesFrom = "";


                $result = ORM::for_table('user_friends')
                ->where(array('user' => $user->getId(), "newMessage" => 1, "accepted" => 1)) 
                ->find_many();

                if($result) {
                    foreach($result as $user_friends) {
                        $newMessagesFrom = $newMessagesFrom . $user_friends->friend . ", ";
                    }
                }

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'onlineUsers' => $onlineUsers,
                        'newMessages' => $newMessagesFrom
                    ));

                exit;
            }

            if(isset($_MyPost->pullMessages)) {

                $chat = Chat::getInstance($_MyPost->userId);

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'messages' => $chat->pullMessagesFormatted()
                    ));

                exit;
            }

        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 404,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
        exit;
    }


    //! Split those in admin and other class ?

    if(isset($_MyPost->admin)) {
        try {
            if(isset($_MyPost->userDetails)) {

                if(isset($_MyPost->userId)) {
                    $user = User::byId($_MyPost->userId);
                    $data = Render::renderPageContents($user, "AdminUsersDetails");
                }
                else {
                    $data = Render::renderPageContents(null, "AdminUsersDetailsNew");
                }
                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'userDetails' => $data
                    ));
            }

            if(isset($_MyPost->changeDetails)) {

                $user = User::byId($_MyPost->userId);
                $user->adminUpdateDetails($_MyPost);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }

            if(isset($_MyPost->addUser)) {

                $user = User::newUser($_MyPost->username, "Default", "", $_MyPost->email, $_MyPost->password);
                $user->adminUpdateDetails($_MyPost);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }

            if(isset($_MyPost->deleteUser)) {

                $user = new User();
                
                if(intval($user->getId()) != intval($_MyPost->userId)) {
                    User::deleteUser($user->getId(), intval($_MyPost->userId));
                }

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }
        }
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 404,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
    exit;

    }

    if(isset($_MyPost->stats)) {

        try {
            if(isset($_MyPost->loggedUsersChars)) {
            
            $result = ORM::for_table('userstatistics')
            ->select_many_expr('user',
             array('date' => 'DATE_FORMAT(date, "%d/%m")'))
            ->find_many();

            $dataArray = "";

            if($result) {
                foreach($result as $row) {
                    $dataArray = $dataArray . 
                    '<tr style="height:' .  10 * intval($row->user) . '%">
                    <th scope="row">' . $row->date . '</th>
                    <td>
                        <span>' . $row->user . '</span>
                        <div title="' . $row->user . ' Users on ' . $row->date . '" class="chartTooltip"></div>
                    </td>
                    </tr>';
                }   
            }

            echo json_encode(
                array(
                    'statusCode' => 200,
                    'elementInner' => $dataArray
                ));
            }

            if(isset($_MyPost->onlineUsers)) {

                $result = ORM::for_table('users')
                ->where(array('online' => 1)) 
                ->count();
                
                if($result) {
                    echo json_encode(
                        array(
                            'statusCode' => 200,
                            'online' => $result
                        ));
                }
                else {
                    echo json_encode(
                        array(
                            'statusCode' => 200,
                            'online' => 0
                        ));  
                }

            }

            if(isset($_MyPost->usersTable)) {

                if(isset($_MyPost->pattern)) {
                    $result =  ORM::for_table('users')
                    ->inner_join('user_details', 'users.id = user_details.user')
                    ->inner_join('privileges', 'users.id = privileges.user')
                    ->where_like('user_details.last_name', '%' . $_MyPost->pattern .'%')
                    ->find_many();
                }
                else {
                    $result = ORM::for_table('users')
                    ->inner_join('user_details', 'users.id = user_details.user')
                    ->inner_join('privileges', 'users.id = privileges.user')->find_many();
                }

                $dataArray = "";

                if($result) {
                    foreach($result as $row) {
                        $dataArray = $dataArray . 
                        '<tr>
                        <td data-label="ID">' . $row->id . '</td>
                        <td data-label="Username">' . $row->name . '</td>
                        <td data-label="Last Name">' . $row->last_name . '</td>
                        <td data-label="Email">' . $row->email . '</td>
                        <td data-label="Phone">' . $row->phone_number . '</td>
                        <td data-label="Admin">' . ((intval($row->is_admin) == 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>') . '</td>
                        <td data-label="Online">' . ((intval($row->online) == 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>') . '</td>
                        <td data-label="Options">
                            <button onclick="adminEditUser(this); return false;" class="formattedButton"><i class="fas fa-lg fa-edit"></i></button>
                            <button onclick="adminDeleteUser(this); return false;" class="formattedButton"><i class="fas fa-lg fa-user-minus"></i></button></td>
                    </tr>';
                    }   
                }

                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'usersTable' => $dataArray
                    ));
            }

        } 
        catch (Exception $e) {
            echo json_encode(
                array(
                    'statusCode' => 404,
                    'Caught exception: ' => $e->getMessage()
                ));
        }
        exit;
    }


