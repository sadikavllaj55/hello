<?php

require '../functions.inc.php';
require '../database/posts.php';
require '../database/images.php';
require '../database/users.php';

const MAX_FILE_SIZE = 4 * 1024 * 1024; // 4 MB

function edit_profile()
{
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
            $current_user = get_user($_SESSION['user']['id']);
            if ($current_user['image_id'] != null) {
                $image_id = replace_image(
                    $current_user['image_id'],
                    $_SESSION['user']['id'],
                    '../../uploads/profile/',
                    'profile_image',
                    $conn
                );
            } else {
                $image_id = upload_image('../../uploads/profile/', 'profile_image', $conn);
            }

            if ($image_id != false) {
                $sql = "UPDATE users SET username=?, email=?, country=?, image_id=? WHERE id=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: /new/edit.php?error=sqlerror");
                } else {
                    mysqli_stmt_bind_param($stmt, "sssii",
                        $username,
                        $email,
                        $country,
                        $image_id,
                        $id
                    );
                    mysqli_stmt_execute($stmt);

                    $current_user = get_user($_SESSION['user']['id']);

                    $_SESSION['user']['username'] = $username;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['country'] = $country;
                    $_SESSION['user']['profile_image'] = $current_user['profile_image'];
                    header("Location: /new/edit.php?status=success");
                }
            } else {
                header("Location: /new/post.php?error=uploadfailed");
            }
        }
    }
}

edit_profile();