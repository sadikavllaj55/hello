<?php

require '../functions.inc.php';



if (isset($_POST['login-submit'])) {
    $conn = db_connect();

    $mailuid = $_POST["mailuid"];
    $password = $_POST["pwd"];

    if (empty($mailuid) || empty($password)) {
        header('Location:/new/login.php?error=emptyfields');
    } else {
        $sql = "
            SELECT users.*, images.path AS profile_image
            FROM users
                LEFT JOIN images ON users.image_id=images.id
            WHERE users.username = ? OR users.email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location:/new/index.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['password']);
                if ($pwdCheck === false) {
                    header("Location:/new/login.php?error=wrongpwd");
                } else {
                    session_start();
                    $_SESSION['user'] = [
                        'id' => $row['id'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'country' => $row['country'],
                        'profile_image' => $row['profile_image']
                    ];

                    header("Location:/new/index.php?login=success");

                }
            } else {
                header("Location:/new/login.php?error=noresultsfromdb");
            }
        }
    }
} else {
    header("Location:/new/index.php");
}