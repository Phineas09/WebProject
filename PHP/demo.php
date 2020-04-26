<?php

    //Always return json with statusCode variable set @!#$@!

    require_once("./idiorm.php");
    require_once("./smtp.php");
    require_once("./Logger.php");
    require_once("./PageChanger.php");
    require_once("./ProblemsManager.php");
    require_once('./User.php');
    require_once('./Render.php');


	ORM::configure('mysql:host=localhost;dbname=mtarena');
	ORM::configure('username', 'testServer');
	ORM::configure('password', '0GFCZeZSmOdJ5Oaj');

    if(session_id() == '') {
        session_start();
    }

    $problemManager = new ProblemsManager();

    $db = ORM::get_db();

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
            difficulty TEXT DEFAULT 'easy'
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            presentation TEXT NOT NULL,
            CONSTRAINT fk_author_id FOREIGN KEY (author) REFERENCES users(id)
    );";

    $problems_solved = "
        CREATE TABLE IF NOT EXISTS problems_solved (
            id INTEGER AUTO_INCREMENT PRIMARY KEY,
            user INTEGER NOT NULL,
            problem INTEGER NOT NULL,
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

// ! To do ProblemsDetails and Problems IO files, pictures and others ????????????

    $db->exec($users . $problems . $problems_solved . $problems_pending . $problems_approvedBy);
    $db->exec($logs);
    $db->exec($logs_view);
    $db->exec($privilege);
    $db->exec($user_details);

    header("Content-Type: application/json");
    // build a PHP variable from JSON sent using POST method

///!


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
            $user->markLastAct();
            
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


    if(isset($_MyPost->loadProblems)) {
        try {
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
                $currentUser = new User();
                $pageContent = Render::renderPageContents($currentUser, $_MyPost->page);
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
                $user = new User();
                echo json_encode(
                    array(
                        'statusCode' => 200,
                        'navbar' => $user->getUserNavbar()
                    ));
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













