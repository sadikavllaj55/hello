<?php

require_once 'connection.php';

function get_image(int $id)
{
    $connection = db_connect();

    $sql = "SELECT *
            FROM images   
            WHERE id=?
            ";

    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    }
    mysqli_stmt_bind_param($stmt, "i", $id,);
    $result = mysqli_stmt_execute($stmt);

    if ($result === false) {
        echo mysqli_error($connection);
        return null;
    } else {
        return mysqli_fetch_assoc($result);
    }
}

function delete_image($id, $author_id)
{
    $connection = db_connect();

    $image = get_image($id);

    $sql = "DELETE 
            FROM images 
            WHERE id=? AND uploaded_by=?
            ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    }

    mysqli_stmt_bind_param($stmt, "ii", $id, $author_id);
    $result = mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($connection) == 1) {
        unlink(ROOT_DIR . $image['path']);
        return $result;
    } else {
        return false;
    }
}

function upload_image($upload_dir, $input_name, $connection)
{
    if (!is_dir($upload_dir)) {
        // dir doesn't exist, make it
        mkdir($upload_dir, 0777, true);
    }

    $root_dir = realpath('../../');

    $image_file = $_FILES[$input_name];

    $image_file_extension = $_FILES[$input_name]['name'];
    $image_extions = pathinfo($image_file_extension, PATHINFO_EXTENSION);

    if (filesize($image_file['tmp_name']) > MAX_FILE_SIZE) {
        header("Location: /new/post.php?error=imagesizetoobig");
    }

    $filename = bin2hex(random_bytes(20));
    $new_filename = $filename . '.' . $image_extions;

    $copied = move_uploaded_file($image_file['tmp_name'], $upload_dir . $new_filename);

    if ($copied) {
        $image_path = realpath($upload_dir . $new_filename);
        chmod($image_path, 0777);
        $relative_image_path = str_replace($root_dir, '', $image_path);
        $image_sql = "INSERT INTO images(original_name, path, filesize, uploaded_by) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($connection);

        mysqli_stmt_prepare($stmt, $image_sql);

        mysqli_stmt_bind_param(
            $stmt, "sssi",
            $image_file['name'],
            $relative_image_path,
            $image_file['size'],
            $_SESSION['user']['id']
        );

        $exec = mysqli_stmt_execute($stmt);

        if ($exec) {
            return mysqli_insert_id($connection);
        } else {
            return false;
        }
    }

    return false;
}

function replace_image($image_id, $user_id, $upload_dir, $input_name, $connection)
{
    $deleted = delete_image($image_id, $user_id);

    if ($deleted) {
        return upload_image($upload_dir, $input_name, $connection);
    } else {
        return false;
    }
}