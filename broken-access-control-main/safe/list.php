<?php
// safe/list.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$stmt = $pdo->prepare("SELECT id, uuid, title, created_at FROM items_safe WHERE user_id = :u ORDER BY created_at DESC");
$stmt->execute([':u' => $_SESSION['user']['id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SAFE — Items (Your Items)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root {
            --bg: #f0f7f4;
            --card: #f8fdf9;
            --text: #1a3c34;
            --muted: #5d7a6f;
            --accent: #2d9d78;
            --success: #27ab83;
            --table-head: #e8f6ef;
            --border: #d4e8de;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            margin: 0;
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f4ee 100%);
            color: var(--text);
            padding: 28px;
            min-height: 100vh;
        }
        .wrap {
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
        }
        .sub {
            color: var(--muted);
            font-size: 14px;
            margin-top: 4px;
        }
        .controls {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn {
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-create {
            background: linear-gradient(135deg, var(--accent), #238c6c);
            color: white;
            box-shadow: 0 4px 12px rgba(45, 157, 120, 0.2);
        }
        .btn-create:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(45, 157, 120, 0.3);
        }
        .btn-back {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-back:hover {
            background: var(--field);
            border-color: var(--accent);
            color: var(--accent);
        }
        .card {
            background: var(--card);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 12px 40px rgba(23, 92, 70, 0.08);
            border: 1px solid var(--border);
        }
        .table-wrap {
            overflow: auto;
            margin-top: 16px;
            border-radius: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
            background: transparent;
        }
        thead th {
            text-align: left;
            padding: 16px 20px;
            background: var(--table-head);
            font-weight: 600;
            font-size: 14px;
            color: var(--text);
            border-bottom: 2px solid var(--border);
        }
        tbody td {
            padding: 16px 20px;
            vertical-align: top;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        tbody tr {
            transition: all 0.2s ease;
        }
        tbody tr:hover {
            background: rgba(45, 157, 120, 0.04);
            transform: translateY(-1px);
        }
        .uuid {
            width: 280px;
            font-family: 'Monaco', 'Consolas', monospace;
            color: #0f5132;
            font-size: 13px;
        }
        .title {
            width: 320px;
            font-weight: 600;
            color: var(--text);
        }
        .created {
            width: 180px;
            color: var(--muted);
            font-size: 13px;
        }
        .actions {
            width: 200px;
            text-align: right;
        }
        .link-action {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            padding: 6px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .link-action:hover {
            background: rgba(45, 157, 120, 0.1);
            color: #238c6c;
        }
        form.inline {
            display: inline-block;
            margin: 0;
        }
        form.inline button {
            background: transparent;
            border: 1px solid var(--border);
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: var(--muted);
            font-size: 13px;
            transition: all 0.2s ease;
        }
        form.inline button:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }
        .note {
            margin-top: 20px;
            color: var(--muted);
            font-size: 13px;
            padding: 12px 16px;
            background: rgba(45, 157, 120, 0.05);
            border-radius: 8px;
            border-left: 4px solid var(--accent);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        @media(max-width:880px) {
            table { min-width: 700px; }
            header { flex-direction: column; align-items: flex-start; }
            .controls { width: 100%; justify-content: flex-end; }
        }
        @media(max-width:600px) {
            body { padding: 16px; }
            .card { padding: 20px; }
            .controls { flex-direction: column; width: 100%; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div>
                <h1>SAFE — Your Items</h1>
                <div class="sub">Hanya menampilkan item milik Anda. UUID & CSRF digunakan untuk operasi sensitif.</div>
            </div>
            <div class="controls" role="group" aria-label="Controls">
                <a class="btn btn-create" href="create.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Create New
                </a>
                <a class="btn btn-back" href="../index.php">← Dashboard</a>
            </div>
        </header>

        <div class="card">
            <div class="table-wrap" role="region" aria-labelledby="tableTitle">
                <table aria-describedby="tableDesc">
                    <thead>
                        <tr>
                            <th class="uuid">UUID</th>
                            <th class="title">Title</th>
                            <th class="created">Created</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                        <tr>
                            <td class="uuid"><?= e($r['uuid']) ?></td>
                            <td class="title"><?= e($r['title']) ?></td>
                            <td class="created"><?= e($r['created_at']) ?></td>
                            <td class="actions">
                                <a class="link-action" href="view.php?u=<?= urlencode($r['uuid']) ?>" title="View">View</a>
                                <a class="link-action" href="edit.php?u=<?= urlencode($r['uuid']) ?>" title="Edit">Edit</a>
                                <form action="delete.php" method="post" class="inline" onsubmit="return confirm('Delete this item permanently?')">
                                    <input type="hidden" name="uuid" value="<?= e($r['uuid']) ?>">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <button type="submit" title="Delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                                </svg>
                                <div>You don't have any items yet.</div>
                                <div style="margin-top:8px;font-size:12px;">Create your first item to get started.</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="note" id="tableDesc">
                ✅ SAFE area: operations require UUID (unguessable) and CSRF token for destructive actions. 
                Good for preventing IDOR & CSRF attacks.
            </div>
        </div>
    </div>
</body>
</html>