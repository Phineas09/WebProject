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
            user INTEGER NOT NULL,
            problem INTEGER NOT NULL,
            CONSTRAINT fk_user_id FOREIGN KEY (user) REFERENCES users(id),
            CONSTRAINT fk_problem_id FOREIGN KEY (problem) REFERENCES problems(id)
    );";   
    
    $problems_pending = "
        CREATE TABLE IF NOT EXISTS problems_pending (
            problem INTEGER NOT NULL,
            CONSTRAINT fk_pending_id FOREIGN KEY (problem) REFERENCES problems(id)
    );";

    $problems_approvedBy = "
        CREATE TABLE IF NOT EXISTS problems_approvedBy (
            user INTEGER NOT NULL,
            problem INTEGER NOT NULL,
            CONSTRAINT fk_approvedBy_id FOREIGN KEY (user) REFERENCES users(id),
            CONSTRAINT fk_problemApproved_id FOREIGN KEY (problem) REFERENCES problems(id) 
    );";

    $logs = "
        CREATE TABLE IF NOT EXISTS logs (
            user INTEGER NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT FK_USER_LOGS_ID FOREIGN KEY (user) REFERENCES users(id)
    );";

    $logs_view = "
        CREATE OR REPLACE VIEW userStatistics AS 
            SELECT
            user,
            DATE(date) as day
            FROM logs
            GROUP by user, day
            ORDER BY day
    ;";  

    $privilege = "
        CREATE TABLE IF NOT EXISTS privileges (
            user INTEGER NOT NULL,
            is_admin BOOLEAN DEFAULT false,
            can_modify BOOLEAN DEFAULT false,
            can_approve BOOLEAN DEFAULT false,
            CONSTRAINT fk_user_privileges FOREIGN KEY (user) REFERENCES users(id)
    );";


// ! To do ProblemsDetails and Problems IO files, pictures and others ????????????

    $db->exec($users . $problems . $problems_solved . $problems_pending . $problems_approvedBy);
    $db->exec($logs);
    $db->exec($logs_view);
    $db->exec($privilege);

    header("Content-Type: application/json");
    // build a PHP variable from JSON sent using POST method

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

    if(isset($_MyPost->pageChanger)) {
        $changer = new PageChanger();
        return $changer->handler($_MyPost);
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
            $pageContent = PageChanger::getPageContents($_MyPost->page);
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
                        'statusCode' => 415,
                        'Caught exception: ' => $e->getMessage()
                    ));
            }
            exit;
        }

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














