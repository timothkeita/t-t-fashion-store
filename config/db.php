<?php

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
| Uses Docker settings when running inside Docker.
| Uses XAMPP settings when running locally.
|--------------------------------------------------------------------------
*/

if (getenv('APP_ENV') === 'docker') {

    // Docker
    $host = "mysql";
    $user = "fashion";
    $password = "fashion123";
    $database = "tt_fashion_store";

} else {

    // XAMPP
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "tt_fashion_store";
}

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");