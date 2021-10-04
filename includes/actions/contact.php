<?php


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
if (isset($_POST['email'])) {
session_start();
    $to = $_POST['mail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
$from  = $_SESSION['user']['email'];



    try {


        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        /*$mail->Mailer = "smtp";*/
        $mail->Username = $from;
        $mail->Password = "sadikavllaj1";                        //OPmyuiRTYfyh54
        $mail->SMTPSecure = "ss1";
        $mail->Port = 587;
        $mail->SMTPDebug = 2;

        $mail->SetFrom($from);
        $mail->AddAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        /*$mail->IsHTML(true);


        $mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
        $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");*/

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    header("Location: /new/index.php");

}



