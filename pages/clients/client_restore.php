<?php
include "../../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: deleted_clients.php");
    exit;
}

$id = intval($_GET['id']);
$db->query("UPDATE clients SET deleted=0 WHERE id=$id");
header("Location: deleted_clients.php?msg=Client restored successfully");
exit;
