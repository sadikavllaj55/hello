<?php
if (isset($_POST['email'])) {

    $to = $_POST['mail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

   mail($to, $subject, $message);

    header("Location: /new/index.php");

}



