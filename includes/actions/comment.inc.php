<?php

require '../functions.inc.php';
require '../database/comments.php';

$action = $_POST['action'] ?? '';

if ($action == 'delete_comment') {
    delete_comment_action(); // This needs to be added here; see includes/actions/posts.inc.php
} elseif ($action == 'new_comment') {
    new_comment_action();
}

function delete_comment_action() {
    session_start();
    $comment_id = $_POST['comment'];
    $author_id = $_SESSION['user']['id'];
    $current_url = $_SERVER['HTTP_REFERER'];
    $deleted = delete_comment($comment_id, $author_id);

    if ($deleted) {
        $url_add = 'status=success&type=deleted_comment';
    } else {
        $url_add = 'status=error&type=error_deleted_comment';
    }

    if (stripos($current_url, '?') !== false) {
        $redirect_url = $current_url . '&' . $url_add;
    } else {
        $redirect_url = $current_url . '?' . $url_add;
    }
    header('Location:' . $redirect_url);
}

function new_comment_action() {
    if (isset($_POST['comments-submit'])) {
        $conn = db_connect();
        session_start();

        if (!is_logged_in()) {
            header('Location:/new/login.php?error=new_comment');
        }

        $description = $_POST['description'];
        $author = $_SESSION['user']['id'];
        $id_post = $_POST['postId'];

        $sql = "INSERT INTO comments (description,user_id,post_id) VALUES (?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $description, $author, $id_post);
        $exec = mysqli_stmt_execute($stmt);
        var_export($exec);

        $current_url = $_SERVER['HTTP_REFERER'];

        if ($exec) {
            $url_add = 'status=success&type=new_comment';
        } else {
            $url_add = 'status=error&type=error_new_comment';
        }

        if (stripos($current_url, '?') !== false) {
            $redirect_url = $current_url . '&' . $url_add;
        } else {
            $redirect_url = $current_url . '?' . $url_add;
        }

        header('Location:' . $redirect_url);
    } else {
        echo "no";
    }
}
