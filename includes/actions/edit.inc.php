<?php

require '../functions.inc.php';

if (isset($_POST['edit'])) {
    $conn = db_connect();

    session_start();

    $username = $_POST['username'];
    $email = $_POST['mail'];
    $id = $_SESSION['user']['id'];
    $country = $_POST['country'];

    if (empty($username) || empty($email)) {
        $_SESSION['errors']['emptyfield'] = ['uid' => $username, 'mail' => $email];
        header("Location: /new/edit.php?error=emptyfields&uid=" . $username . "&mail=" . $email);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: /new/edit.php?error=invalidmailuid");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /new/edit.php?error=invalidmail&uid=" . $username);
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: /new/edit.php?error=invaliduid&mail=" . $email);
    } else {
        $sql = "
                UPDATE users 
                SET username=?, email=?, country=? 
                WHERE username=?
                ";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: /new/edit.php?error=sqlerror");
        } else {
            mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $country, $id);
            mysqli_stmt_execute($stmt);
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['country'] = $country;
            header("Location: /new/edit.php?login=success");
        }
    }
}