<?php

require('../functions.inc.php');

if (isset($_POST['signup-submit'])) {
    $conn = db_connect();

    $username = $_POST['username'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];
    $country = $_POST['country'];


    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $_SESSION['errors']['emptyfield'] = ['username' => $username, 'mail' => $emails];
        header("Location: /new/signup.php?error=emptyfields&uid=" . $username . "&mail=" . $email);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        header("Location: /new/signup.php?error=invalidmailuid");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /new/signup.php?error=invalidmail&uid=" . $username);
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        header("Location: /new/signup.php?error=invaliduid&mail=" . $email);
    } elseif (strlen($password) < 8) {
        header("Location: /new/signup.php?error=passwordtooshort");
    } elseif (!preg_match("#[0-9]+#", $password)) {
        header("Location: /new/signup.php?error=passwordmustincludeatleastonenumber");
    } elseif (!preg_match("#[a-zA-Z]+#", $password)) {
        header("Location: /new/signup.php?error=passwordmustincludeatleastoneletter");
    } elseif ($password !== $passwordRepeat) {
        header("Location: /new/signup.php?error=passwordcheckuid");
    } else {
        $sql = "SELECT username FROM users WHERE email=? or username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: /new/signup.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $email, $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: /new/signup.php?error=usertaken&mail=");
            } else {
                //regjistro nje perdorues te ri
                $sql = "INSERT INTO users(username,email,password,country) VALUES(?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: /new/signup.php?error=sqlerrorr");
                } else {
                    $hashedPwd = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

                    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPwd, $country);
                    mysqli_stmt_execute($stmt);
                    session_start();
                    $_SESSION['user'] = [
                        'id' => mysqli_insert_id($conn),
                        'username' => $username,
                        'email' => $email,
                        'country' => $country,
                        'profile_image' => null
                    ];

                    $_SESSION['new_signup'] = true;
                    header("Location:/new/index.php?login=success");
                }
            }
        }
    }
mysqli_stmt_close($stmt);
mysqli_close($conn);

} else {
    header("Location: /new/signup.php");
    exit();
}
