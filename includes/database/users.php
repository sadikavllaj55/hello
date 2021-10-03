<?php

require_once 'connection.php';

function get_user($id)
{
    $connection = db_connect();

    $sql = "
        SELECT users.*, images.path as profile_image
        From users 
            LEFT JOIN images on images.id = users.image_id
        WHERE users.id = ?
        ";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    } else {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    return null;
}