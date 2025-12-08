<?php
include "../../../includes/db.php";

$client_id = $_POST['client_id'];
$message   = $_POST['message'];
$sender    = "Admin";

$stmt = $db->prepare("INSERT INTO client_chats (client_id,message,sender,created_at) VALUES (?,?,?,NOW())");
$stmt->bind_param("iss", $client_id, $message, $sender);
$stmt->execute();
