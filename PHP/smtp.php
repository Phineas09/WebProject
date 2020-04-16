<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'C:/xampp/composer/vendor/autoload.php';

    function sendNewPassword($newPassword, $name, $email) {

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->SMTPSecure = 'tsl';
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'mtarenaweb@gmail.com';                     // SMTP username
            $mail->Password   = "Claudiu147!";                               // SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('mtarenaweb@gmail.com', 'MTArena');
            $mail->addAddress($email, $name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password reset';
            $mail->Body    = "Hello, " . $name . " this is your new password! <br><br><b>"  . $newPassword . "</b>";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            throw $e;
            //throw new Exception("Email was not sent!!");
        }

    }

