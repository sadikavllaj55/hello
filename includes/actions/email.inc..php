<?php
if (isset($_POST['email'])) {
    $to = $_POST['mail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

   return mail($to, $subject, $message);

}