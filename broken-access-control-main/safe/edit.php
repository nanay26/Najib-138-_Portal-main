<?php
// safe/edit.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$uuid = $_GET['u'] ?? ($_POST['uuid'] ?? '');
if (!$uuid) {
    http_response_code(400);
    exit('Missing uuid');
}

$stmt = $pdo->prepare("SELECT * FROM items_safe WHERE uuid = :u LIMIT 1");
$stmt->execute([':u' => $uuid]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    http_response_code(404);
    exit('Not found');
}

if ($item['user_id'] != $_SESSION['user']['id']) {
    http_response_code(403);
    exit('Forbidden: not owner');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('CSRF fail');
    }
    
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    $stmt = $pdo->prepare("UPDATE items_safe SET title = :t, content = :c WHERE uuid = :u");
    $stmt->execute([':t' => $title, ':c' => $content, ':u' => $uuid]);
    
    header('Location: list.php');
    exit;
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit SAFE Item — <?= e($item['uuid']) ?></title>
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
            --field: #edf7f2;
            --border: #d4e8de;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            margin: 0;
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f4ee 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .wrap {
            width: 100%;
            max-width: 780px;
        }
        .card {
            background: var(--card);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 12px 40px rgba(23, 92, 70, 0.08);
            border: 1px solid var(--border);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
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
        .uuid-display {
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
            color: var(--muted);
            background: var(--field);
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 8px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            font-weight: 600;
            font-size: 14px;
            color: var(--text);
            margin-bottom: 6px;
            display: block;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--field);
            font-size: 15px;
            outline: none;
            color: var(--text);
            transition: all 0.2s ease;
        }
        textarea {
            min-height: 160px;
            resize: vertical;
            font-family: inherit;
        }
        input:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(45, 157, 120, 0.1);
            background: white;
        }
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 14px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #238c6c);
            color: white;
            box-shadow: 0 4px 12px rgba(45, 157, 120, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(45, 157, 120, 0.3);
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
        }
        .btn-ghost:hover {
            background: var(--field);
            border-color: var(--accent);
            color: var(--accent);
        }
        .note {
            margin-top: 20px;
            padding: 14px 16px;
            background: rgba(45, 157, 120, 0.05);
            border-radius: 12px;
            color: var(--muted);
            font-size: 13px;
            border-left: 4px solid var(--accent);
        }
        @media(max-width:600px) {
            .card { padding: 24px; }
            .actions { flex-direction: column; }
            .btn { width: 100%; }
            header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card" role="main" aria-labelledby="title">
            <header>
                <div>
                    <h1 id="title">Edit SAFE Item</h1>
                    <div class="sub">Hanya pemilik item yang dapat mengedit</div>
                    <div class="uuid-display">UUID: <?= e($item['uuid']) ?></div>
                </div>
                <div>
                    <a href="list.php" class="btn btn-ghost">← Back to list</a>
                </div>
            </header>

            <form method="post" novalidate>
                <div>
                    <label for="title">Title</label>
                    <input id="title" name="title" type="text" value="<?= e($item['title']) ?>" placeholder="Masukkan judul">
                </div>
                
                <div>
                    <label for="content">Content</label>
                    <textarea id="content" name="content" placeholder="Masukkan konten"><?= e($item['content']) ?></textarea>
                </div>
                
                <input type="hidden" name="uuid" value="<?= e($item['uuid']) ?>">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="list.php" class="btn btn-ghost">Cancel</a>
                </div>
                
                <div class="note">
                    ✅ SAFE: Update menggunakan prepared statement dan verifikasi kepemilikan. 
                    UUID digunakan untuk menghindari IDOR attacks.
                </div>
            </form>
        </div>
    </div>
</body>
</html>