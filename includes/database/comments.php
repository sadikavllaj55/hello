<?php

require_once 'connection.php';

function get_all_comments() {
    $connection = db_connect();

    $sql = "SELECT comments.id, comments.description,comments.published,comments.post_id,
                users.username as author 
            FROM comments 
                LEFT JOIN users ON comments.user_id = users.id
            ORDER BY comments.published DESC";
    $result = mysqli_query($connection, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_post_comments($post_id) {
    $connection = db_connect();

    $sql = "SELECT comments.id, comments.description,comments.published,comments.post_id,
                users.username as author, comments.user_id
            FROM comments 
                LEFT JOIN users ON comments.user_id = users.id
            WHERE comments.post_id=?
            ORDER BY comments.published DESC
            ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/index.php?error=sqlerror");
    }

    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function delete_comment($comment_id, $author_id) {
    $connection = db_connect();

    $sql = "DELETE 
            FROM comments 
            WHERE id = ? AND user_id = ?
            ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    }

    mysqli_stmt_bind_param($stmt, "ii", $comment_id, $author_id);
    $result = mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($connection) == 1) {
        return $result;
    } else {
        return false;
    }
}

function get_prof_image($id)
{
    $connection = db_connect();

    $sql = "SELECT images.path 
            FROM images 
                LEFT JOIN users ON users.image_id = images.id
            WHERE users.id =?
             ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/index.php?error=sqlerror");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}