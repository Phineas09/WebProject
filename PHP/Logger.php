<?php

class Logger
{

    public function handler($_MyPost) {

        if(isset($_MyPost->register)) {

            try {
                //require etc etc
                // Create a new user object

                $user = User::byEmail($_MyPost->email);

                if(!$user->isGuest())
                    throw new Exception("Email already exists!");

                $user = User::newUser($_MyPost->name, "Default", "", $_MyPost->email, $_MyPost->password);

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }
            catch (Exception $e) {
                echo json_encode(
                    array(
                        'statusCode' => 409,
                        'Caught exception: ' => $e->getMessage()
                    ));
            }
            exit;
        }

        if(isset($_MyPost->login)) {

            try {

                $user = User::matchAccount($_MyPost->email, $_MyPost->password);    

                if($user->isGuest())
                    throw new Exception("Email or password do not match");

                echo json_encode(
                    array(
                        'statusCode' => 200
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

        if(isset($_MyPost->oauth)) {

            if (isset($_SESSION['Logged']))
            {
                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
                exit;
            }
            try {
                $user = User::matchAccount("", "", $_MyPost->oauthId, $_MyPost->method);    
                if(!$user->isGuest()) {
                    echo json_encode(
                        array(
                            'statusCode' => 200
                        ));
                    exit;
                }
                $user = User::newUser($_MyPost->name, $_MyPost->method, $_MyPost->oauthId, $_MyPost->email, "", false);
                $user->login();
                $user->validateAccount();
                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
                exit;
            }
            catch (Exception $e) {
                echo json_encode(
                    array(
                        'statusCode' => 410,
                        'Caught exception: ' => $e->getMessage()
                    ));
            }
            exit;
        }

        if(isset($_MyPost->loginVerification)) {

            try {
                if (isset($_COOKIE['LoggedUser'])) {

                    $user = new User();

                    if($user->isGuest())
                        throw new Exception("Cookie is corrupted");

                    $user->login();

                    echo json_encode(
                        array(
                            'statusCode' => 200
                        ));
                } else {
                    echo json_encode(
                        array(
                            'statusCode' => 411
                        ));
                }
            }
            catch (Exception $e) {
                User::wipeLoginCookies();
                echo json_encode(
                    array(
                        'statusCode' => 412,
                        'Caught exception: ' => $e->getMessage()
                    ));
            }
            exit;
        }

        if(isset($_MyPost->logOut)) {

            try {
                if (isset($_COOKIE['LoggedUser']) && isset($_SESSION['Logged'])) {

                    $user = new User();

                    if($user->isGuest())
                        throw new Exception("Cookie is corrupted");
                    
                    $user->setStatusOffline();

                    echo json_encode(
                        array(
                            'statusCode' => 200,
                            'oauth' => $user->getOauth()
                        ));
                } else {
                    echo json_encode(
                        array(
                            'statusCode' => 413
                        ));
                }
            }
            catch (Exception $e) {
                User::wipeLoginCookies();
                echo json_encode(
                    array(
                        'statusCode' => 412,
                        'Caught exception: ' => $e->getMessage()
                    ));
                exit;
            }
            if( $_MyPost->logOutOnClose == "false" ) { //only execute when deliberate logout
                User::wipeLoginCookies();
            }

            //! Destroy session here maybe ?

            //? session_destroy();
            exit;
        }

        if(isset($_MyPost->forgotPassword)) {

            try {

                $user = User::byEmail($_MyPost->email);

                if($user->isGuest())
                    throw new Exception("Email or password do not match");

                $user->generateSendNewPassword();    

                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
            }

            catch (Exception $e) {
                echo json_encode(
                    array(
                        'statusCode' => 416,
                        'Caught exception: ' => $e->getMessage()
                    ));
            }
            exit;
        }

    }
}