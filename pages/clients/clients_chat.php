<?php
$activePage = "clients";
include "../../includes/db.php";

// Fetch chat messages safely
$chatStmt = $db->prepare("
    SELECT cc.sender, cc.message, cc.created_at, c.client_name
    FROM client_chats cc
    LEFT JOIN clients c ON cc.client_id = c.id
    ORDER BY cc.created_at ASC
");
$chatStmt->execute();
$chatResult = $chatStmt->get_result();

// Fetch active clients safely
$clientsStmt = $db->prepare("SELECT id, client_name FROM clients WHERE deleted = 0 ORDER BY client_name ASC");
$clientsStmt->execute();
$clientsResult = $clientsStmt->get_result();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Clients Chat | Dantechdevs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background: #f4f6f9;
        }

        .app-shell {
            display: block;
            min-height: 100vh;
        }

        .page-content {
            margin-left: 0;
            padding: 24px;
            transition: margin-left .15s ease;
            min-height: 100vh;
        }

        .chat-box {
            max-height: 420px;
            overflow-y: auto;
            border: 1px solid #e3e6ea;
            padding: 16px;
            background: #fff;
            border-radius: 8px;
        }

        .chat-message {
            margin-bottom: 12px;
        }

        .chat-message .sender {
            font-weight: 700;
            color: #0d6efd;
        }

        .card.shadow-sm {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 991px) {
            .page-content {
                margin-left: 0 !important;
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="app-shell">

        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <!-- Main content -->
        <div class="page-content" id="pageContent">
            <h2 class="mb-4">Clients Chat</h2>

            <div class="chat-box mb-4" id="chatBox">
                <?php while ($chat = $chatResult->fetch_assoc()): ?>
                    <div class="chat-message">
                        <span class="sender"><?= htmlspecialchars($chat['sender']) ?>
                            (<?= htmlspecialchars($chat['client_name']) ?>):
                        </span>
                        <div class="message"><?= nl2br(htmlspecialchars($chat['message'])) ?></div>
                    </div>
                <?php endwhile; ?>
            </div>

            <form method="POST" action="php/send_client_chat.php" class="card p-4 shadow-sm mt-3" id="chatForm">
                <div class="mb-3">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Select Client --</option>
                        <?php while ($c = $clientsResult->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['client_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>
                </div>

                <button class="btn btn-success w-100" type="submit">Send Message</button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const page = document.getElementById('pageContent');

            function computeSidebarWidth() {
                const selectors = ['aside', '#sidebar', '.sidebar', '.main-sidebar', '.left-sidebar'];
                for (let sel of selectors) {
                    let el = document.querySelector(sel);
                    if (!el) continue;
                    let style = window.getComputedStyle(el);
                    if (style.display === 'none' || style.visibility === 'hidden') continue;
                    let rect = el.getBoundingClientRect();
                    if (rect.width > 10) return Math.min(rect.width, window.innerWidth * 0.45);
                }
                return 0;
            }

            function applyMargin() {
                page.style.marginLeft = computeSidebarWidth() + 'px';
            }
            window.addEventListener('load', applyMargin);
            window.addEventListener('resize', applyMargin);
            new MutationObserver(applyMargin).observe(document.body, {
                attributes: true,
                childList: true,
                subtree: true
            });

            // Auto-scroll chat to bottom
            const chatBox = document.getElementById('chatBox');
            if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>