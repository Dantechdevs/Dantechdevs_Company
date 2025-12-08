<?php
include "../../../includes/db.php";

$q = $db->query("
SELECT cc.*, c.client_name 
FROM client_chats cc
LEFT JOIN clients c ON cc.client_id = c.id
ORDER BY cc.created_at ASC
");

while ($row = $q->fetch_assoc()) {
    $type = $row['sender'] === 'Admin' ? 'admin' : 'client';
?>
    <div class="message-row <?= $type ?>">
        <div class="bubble">
            <?= htmlspecialchars($row['message']) ?>
            <div class="msg-time">
                <?= date("H:i", strtotime($row['created_at'])) ?>
            </div>
        </div>
    </div>
<?php } ?>