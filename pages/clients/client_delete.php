<?php
include "../../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: client_list.php");
    exit;
}

$id = intval($_GET['id']);
$db->query("UPDATE clients SET deleted=1 WHERE id=$id");
header("Location: client_list.php?msg=Client deleted successfully");
exit;
