<?php
    include_once(dirname(__DIR__).'/config.php');
    $conn = new mysqli($dbInfo['host'], $dbInfo['username'], $dbInfo['password'], $dbInfo['database']);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
?>