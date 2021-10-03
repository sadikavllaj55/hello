<?php

define("DB_HOST", "localhost");//127.0.0.1
define("DB_NAME", "atis");
define("DB_USER", "sadiku");
define("DB_PASSWORD", "password");

function db_connect()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_error()) {
        echo mysqli_connect_error();
        exit;
    }
    return $conn;
}
