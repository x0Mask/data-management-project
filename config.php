<?php

$serername = "mysql";
$username = "user";
$password = "123456";
$dbname = "data_management";

$conn = new mysqli($serername, $username, $password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
