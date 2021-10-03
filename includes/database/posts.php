<?php

require_once 'connection.php';

function get_user_posts($id)
{
    $connection = db_connect();

    $sql = "
        SELECT posts.id, title, images.path as image, created_at, views
        FROM posts 
        LEFT JOIN images ON posts.image_id = images.id
        WHERE author_id=? 
        ORDER BY created_at
        ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    } else {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return [];
}

/**
 * @param int $limit numri i postimeve ne 1 faqe konstante
 * @param int $offset nisja e ciklit te cdo faqje
 * @return array assoc
 */
function get_posts(int $limit = 5, int $offset = 0)
{
    //TODO: perdor prepared query
    $connection = db_connect();

    $sql = "SELECT posts.id, posts.title, images.path as image, posts.views, posts.created_at, users.username as author
            FROM posts 
                LEFT JOIN users ON posts.author_id = users.id 
                LEFT JOIN images ON posts.image_id = images.id 
            ORDER BY posts.created_at DESC 
            LIMIT $offset,$limit
            ";

    $result = mysqli_query($connection, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param int $page nr i faqes
 * @param int $size limiti i percaktuar me lart
 * @return array
 */
function get_posts_per_page(int $page = 1, int $size = 5)
{
    $offset = ($page - 1) * $size;
    return get_posts($size, $offset);
}

/**
 * @param int $size limiti i percaktuar me lart
 * @return int numri total i faqeve ku ceil esht vlera e plote +1
 */
function number_of_pages(int $size)
{
    return (int)ceil((count_posts()) / $size);
}

/**
 * @return int|null nr e postimeve
 */
function count_posts()
{
    $connection = db_connect();

    $sql = "SELECT COUNT(*) 
            FROM posts
            ";

    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        echo mysqli_error($connection);
        return null;
    } else {
        $row = mysqli_fetch_row($result);
        return intval($row[0]);
    }

}

/**
 * @param int $id_post postimi te cilit i rritet kolona e views sa her klikojm ate
 */
function increment_viewers(int $id_post)
{
    $connection = db_connect();

    $sql =  "UPDATE posts 
            SET views = views + 1 
            WHERE id= ?
            ";

     $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    } else {
        mysqli_stmt_bind_param($stmt, "i", $id_post);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

/**
 * @param int $id
 * @return array|null
 */
function get_post(int $id)
{
    $connection = db_connect();

    //TODO: perdor prepared query
    $sql = "SELECT posts.id, posts.title, images.path as image, posts.views, posts.created_at, posts.description, 
                users.username as author, posts.author_id, posts.image_id
            FROM posts    
                LEFT JOIN users ON posts.author_id = users.id 
                LEFT JOIN images on posts.image_id = images.id
            WHERE posts.id=$id
            ";

    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        echo mysqli_error($connection);
        return null;
    } else {
        return mysqli_fetch_assoc($result);
    }
}

/**
 * @param $id_post
 * @param $id_author
 * @return bool|void
 */
function delete_post($id_post, $id_author)
{
    $connection = db_connect();

    $sql = "DELETE 
            FROM posts 
            WHERE id = ? AND author_id = ?
            ";

    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: /new/myposts.php?error=sqlerror");
    } else {
        mysqli_stmt_bind_param($stmt, "ii", $id_post, $id_author);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }
}