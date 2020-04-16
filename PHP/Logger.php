<?php


class Logger
{

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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

    public function handler($_MyPost) {

        if(isset($_MyPost->register)) {

            try {
                //require etc etc
                // Create a new user object

                $exists = ORM::for_table('users')->where('email', $_MyPost->email)->find_one();

                if($exists != false)
                    throw new Exception("Email already exists!");

                $user = $this->createNewUser($_MyPost->name, "Default", "", $_MyPost->email, $_MyPost->password);
                $user->validated = 0;
                $user->save();

                //Send validation email

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

                $user = ORM::for_table('users')
                    ->where(array(
                        'email' => strtolower($_MyPost->email),
                        'password' => hash('sha256', $_MyPost->password)
                    ))
                    ->find_one();

                if($user == false)
                    throw new Exception("Email or password do not match");

                $user->online = 1;
                setcookie("LoggedUser", $user->hash, time()+3600, "/");
                $_SESSION["Logged"] = "true";
                $user->save();

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

            //Register or login from Facebook or Google +

            if (isset($_SESSION['Logged']))
            {
                echo json_encode(
                    array(
                        'statusCode' => 200
                    ));
                exit;
            }

            try {

                $exists = ORM::for_table('users')
                    ->where(array(
                        'oauth' => $_MyPost->method,
                        'oauthId' => $_MyPost->oauthId
                    ))
                    ->find_one();

                if($exists != false) {
                    $exists->online = 1;
                    $exists->save();

                    setcookie("LoggedUser", $exists->hash, time()+3600, "/");
                    $_SESSION["Logged"] = "true";
                    echo json_encode(
                        array(
                            'statusCode' => 200
                        ));
                    exit;
                }

                $user = $this->createNewUser($_MyPost->name, $_MyPost->method, $_MyPost->oauthId, $_MyPost->email, $this->generateRandomString(15));
                $user->validated = 1;
                $user->online = 1;
                $user->save();

                setcookie("LoggedUser", $user->hash, time()+3600, "/");
                $_SESSION["Logged"] = "true";
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
                    $_SESSION["Logged"] = "true";

                    $user = ORM::for_table('users')
                        ->where(array(
                            'hash' => $_COOKIE['LoggedUser']
                        ))
                        ->find_one();

                    if($user == false)
                        throw new Exception("Cookie is corrupted");

                    $user->online = 1;
                    $user->save();

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
                unset($_COOKIE['LoggedUser']);
                setcookie('LoggedUser', null, -1, '/');
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

                    $user = ORM::for_table('users')
                        ->where(array(
                            'hash' => $_COOKIE['LoggedUser']
                        ))
                        ->find_one();

                    if($user == false)
                        throw new Exception("Cookie is corrupted");

                    $user->online = 0;
                    $user->save();

                    echo json_encode(
                        array(
                            'statusCode' => 200,
                            'oauth' => $user->oauth
                        ));
                } else {
                    echo json_encode(
                        array(
                            'statusCode' => 413
                        ));
                }
            }
            catch (Exception $e) {

                unset($_COOKIE['LoggedUser']);
                setcookie('LoggedUser', null, -1, '/');
                //Uset Session variable
                unset($_SESSION["Logged"]);

                echo json_encode(
                    array(
                        'statusCode' => 412,
                        'Caught exception: ' => $e->getMessage()
                    ));
                exit;
            }
            if( $_MyPost->logOutOnClose == "false" ) { //only execute when deliberate logout
                //Unset Cookie
                unset($_COOKIE['LoggedUser']);
                setcookie('LoggedUser', null, -1, '/');
                //Uset Session variable
                unset($_SESSION["Logged"]);
            }

            exit;
        }

        if(isset($_MyPost->forgotPassword)) {

            try {

                $user = ORM::for_table('users')
                    ->where(array(
                        'email' => strtolower($_MyPost->email)
                    ))
                    ->find_one();

                if($user == false)
                    throw new Exception("Email or password do not match");

                //send new password via smt

                $newPassword = $this->generateRandomString(15);
                $user->password = hash('sha256', $newPassword);

                sendNewPassword($newPassword, $user->name, $user->email);

                $user->save();

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