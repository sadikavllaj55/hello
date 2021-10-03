<?php

require('../functions.inc.php');
require '../database/posts.php';
require '../database/images.php';

const MAX_FILE_SIZE = 4 * 1024 * 1024; // 4 MB

$action = $_POST['action'];

if ($action == 'new') {
    new_post_action();
} elseif ($action == 'edit') {
    edit_post_action();
} elseif ($action == 'delete') {
    delete_post_action();
} else {
    header('Location: /new/index.php?status=error&type=invalid_action');
}

function new_post_action()
{
    if (isset($_POST['posts-submit'])) {
        $conn = db_connect();
        session_start();

        $title = $_POST['title'];
        $description = $_POST['content'];
        $user_id = $_SESSION['user']['id'];

        if (empty($title) || empty($description) || !isset($_FILES['image'])) {
            header("Location: /new/post.php?error=emptyfields");
        } else {
            $image_id = upload_image('../../images/', 'image', $conn);
            if ($image_id != false) {
                $sql = "INSERT INTO posts (title,description,author_id,image_id) VALUES (?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param(
                    $stmt, "ssii",
                    $title,
                    $description,
                    $user_id,
                    $image_id
                );
                mysqli_stmt_execute($stmt);
                session_start();
                $_SESSION['id_post'] = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: /new/index.php");
            } else {
                header("Location: /new/post.php?error=uploadfailed");
            }
        }
    }
}

function edit_post_action()
{
    if (isset($_POST['edit'])) {
        $conn = db_connect();
        session_start();

        $title = $_POST['title'];
        $description = $_POST['content'];
        $user_id = $_SESSION['user']['id'];

        if (empty($title) || empty($description) || !isset($_FILES['image'])) {
            header("Location: /new/post.php?error=emptyfields");
        } else {
            $current_user = get_user($_SESSION['user']['id']);
            $image_id = replace_image(
                    $current_user['image_id'],
                    $_SESSION['user']['id'],
                    '../../images/',
                    'image',
                    $conn
                );

            if ($image_id != false) {
                $sql = "UPDATE posts SET title =?,description =? WHERE image_id=? AND author_id =?  ";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param(
                    $stmt, "ssii",
                    $title,
                    $description,
                    $image_id,
                    $user_id,
                );
                mysqli_stmt_execute($stmt);
                header("Location: /new/index.php");
            } else {
                header("Location: /new/post.php?error=uploadfailed");
            }
        }
    }
}

function delete_post_action() {
    session_start();
    $post_id = $_POST['post_id'];
    $author_id = $_SESSION['user']['id'];

    $post = get_post($post_id);

    if (intval($post['author_id']) == $author_id) {
        $deleted = delete_post($post_id, $author_id);

        if ($deleted) {
            delete_image($post['image_id'], $author_id);
            header('Location: /new/index.php?status=success&type=post_deleted');
        } else {
            header('Location: /new/index.php?status=error&type=error_post_deleted');
        }
    } else {
        header('Location: /new/index.php?status=error&type=error_delete_permission');
    }
}
