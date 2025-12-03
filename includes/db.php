<?php
$host = "localhost";
$user = "root";
$pass = ""; // your MySQL password
$db_name = "dantechdevs_system"; // <-- updated database name

$db = new mysqli($host, $user, $pass, $db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$db->set_charset("utf8");
