<?php
    error_reporting(0);
    $host = 'host name';
    $user = 'db username';
    $passwd = 'db password';
    $db = 'db name';
    $conn = new mysqli($host, $user, $passwd, $db);

    if (!$conn) {
        die("Error in establishing databse connection!");
    }
?>
