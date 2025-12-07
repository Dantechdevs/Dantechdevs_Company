<?php
$activePage = "projects";
include "../../includes/db.php";

// Check if 'id' is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: project_list.php?msg=Invalid project ID");
    exit;
}

$project_id = intval($_GET['id']);

// Optional: Fetch project first to verify it exists
$projectQuery = $db->query("SELECT * FROM projects WHERE id = $project_id");
if (!$projectQuery || $projectQuery->num_rows === 0) {
    header("Location: project_list.php?msg=Project not found");
    exit;
}

// Delete the project
$deleteQuery = $db->query("DELETE FROM projects WHERE id = $project_id");

if ($deleteQuery) {
    header("Location: project_list.php?msg=Project deleted successfully");
    exit;
} else {
    header("Location: project_list.php?msg=Error deleting project: " . urlencode($db->error));
    exit;
}
