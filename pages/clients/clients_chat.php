<?php
$activePage = "clients";
include "../../includes/db.php";
include "sidebar.php";

// Simple chat table structure assumed: client_chats (id, client_id, message, sender, created_at)
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Clients Chat | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .chat-box {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        background: #f9f9f9;
    }

    .chat-message {
        margin-bottom: 10px;
    }

    .chat-message .sender {
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="main-content">
        <h2>Clients Chat</h2>
        <div class="chat-box">
            <?php
        $chatQuery = $db->query("SELECT cc.*, c.client_name FROM client_chats cc LEFT JOIN clients c ON cc.client_id=c.id ORDER BY cc.created_at ASC");
        while($chat = $chatQuery->fetch_assoc()):
        ?>
            <div class="chat-message">
                <span class="sender"><?= htmlspecialchars($chat['sender']) ?>
                    (<?= htmlspecialchars($chat['client_name']) ?>): </span>
                <span class="message"><?= htmlspecialchars($chat['message']) ?></span>
            </div>
            <?php endwhile; ?>
        </div>

        <form method="POST" action="php/send_client_chat.php" class="mt-3">
            <div class="mb-3">
                <label>Client</label>
                <select name="client_id" class="form-select" required>
                    <option value="">-- Select Client --</option>
                    <?php
                $clients = $db->query("SELECT id, client_name FROM clients WHERE deleted=0 ORDER BY client_name ASC");
                while($c=$clients->fetch_assoc()):
                ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['client_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="2" required></textarea>
            </div>
            <button class="btn btn-success">Send</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>