<?php
$activePage = "projects";
include "../../includes/db.php";

// Check if 'id' is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: project_list.php?msg=Invalid project ID");
    exit;
}

$project_id = intval($_GET['id']);

// Check if the project exists and is deleted
$projectQuery = $db->query("SELECT * FROM projects WHERE id = $project_id AND deleted = 1");
if (!$projectQuery || $projectQuery->num_rows === 0) {
    header("Location: project_list.php?msg=Project not found or not deleted");
    exit;
}

// Restore the project
$restoreQuery = $db->query("UPDATE projects SET deleted = 0 WHERE id = $project_id");

if ($restoreQuery) {
    header("Location: project_list.php?msg=Project restored successfully");
    exit;
} else {
    header("Location: project_list.php?msg=Error restoring project: " . urlencode($db->error));
    exit;
}
