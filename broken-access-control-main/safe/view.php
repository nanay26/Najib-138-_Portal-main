<?php
// safe/view.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

$uuid = $_GET['u'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uuid = $_POST['u'] ?? '';
    $token = $_POST['token'] ?? '';
} else {
    $token = $_GET['t'] ?? '';
}

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

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

if (!$token): ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Verifikasi Token — SAFE View</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root {
            --bg: #f0f7f4;
            --card: #f8fdf9;
            --text: #1a3c34;
            --muted: #5d7a6f;
            --accent: #2d9d78;
            --danger: #dc2626;
            --field: #edf7f2;
            --border: #d4e8de;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            margin: 0;
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f4ee 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: var(--card);
            border-radius: 20px;
            padding: 32px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 12px 40px rgba(23, 92, 70, 0.08);
            border: 1px solid var(--border);
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 8px;
        }
        p {
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .uuid-display {
            font-family: 'Monaco', 'Consolas', monospace;
            background: var(--field);
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            color: var(--text);
            margin: 16px 0;
            border: 1px solid var(--border);
        }
        label {
            font-weight: 600;
            font-size: 14px;
            color: var(--text);
            display: block;
            margin-bottom: 8px;
        }
        input {
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
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(45, 157, 120, 0.1);
            background: white;
        }
        button {
            margin-top: 20px;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), #238c6c);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            font-size: 15px;
        }
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(45, 157, 120, 0.3);
        }
        a {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
        }
        a:hover {
            color: var(--accent);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Masukkan Access Token</h1>
        <p>Untuk melihat item dengan UUID:</p>
        <div class="uuid-display"><?= e($uuid) ?></div>
        
        <form method="post">
            <input type="hidden" name="u" value="<?= e($uuid) ?>">
            
            <label for="token">Access Token:</label>
            <input id="token" name="token" type="text" placeholder="Tempelkan token di sini" required>
            
            <button type="submit">Lihat Item</button>
        </form>
        
        <a href="list.php">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali ke List
        </a>
    </div>
</body>
</html>
<?php exit; endif;

$provided_hash = token_hash($token);
if (!hash_equals($item['token_hash'], $provided_hash)) {
    http_response_code(403);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Token Salah</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root {
            --bg: #fef2f2;
            --card: #fef2f2;
            --text: #7f1d1d;
            --muted: #dc2626;
            --border: #fecaca;
        }
        body {
            font-family: 'Poppins', system-ui;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: var(--text);
            padding: 20px;
        }
        .msg {
            background: white;
            padding: 32px;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(220, 38, 38, 0.1);
            border: 1px solid var(--border);
            text-align: center;
            max-width: 400px;
        }
        h2 {
            margin: 0 0 16px 0;
            color: var(--muted);
            font-size: 20px;
        }
        p {
            margin: 8px 0;
            color: var(--text);
        }
        a {
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            margin-top: 16px;
            display: inline-block;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        a:hover {
            background: var(--bg);
            color: #7f1d1d;
        }
    </style>
</head>
<body>
    <div class="msg">
        <h2>❌ Invalid Token</h2>
        <p>Token yang Anda masukkan tidak valid atau sudah tidak berlaku.</p>
        <a href="view.php?u=<?= e($uuid) ?>">Coba lagi</a>
    </div>
</body>
</html>
<?php exit; } ?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= e($item['title']) ?> — SAFE View</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root {
            --bg: #f0f7f4;
            --card: #f8fdf9;
            --text: #1a3c34;
            --muted: #5d7a6f;
            --accent: #2d9d78;
            --border: #d4e8de;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            margin: 0;
            font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f4ee 100%);
            color: var(--text);
            padding: 24px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }
        .card {
            background: var(--card);
            border-radius: 20px;
            padding: 32px;
            max-width: 780px;
            width: 100%;
            box-shadow: 0 12px 40px rgba(23, 92, 70, 0.08);
            border: 1px solid var(--border);
        }
        h1 {
            margin-top: 0;
            color: var(--accent);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .content {
            white-space: pre-line;
            font-size: 16px;
            line-height: 1.7;
            color: var(--text);
            margin-bottom: 24px;
        }
        .uuid {
            margin-top: 24px;
            color: var(--muted);
            font-size: 14px;
            padding: 12px 16px;
            background: rgba(45, 157, 120, 0.05);
            border-radius: 8px;
            border-left: 4px solid var(--accent);
        }
        a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        a:hover {
            background: rgba(45, 157, 120, 0.1);
            border-color: var(--accent);
        }
        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= e($item['title']) ?></h1>
        <div class="content"><?= nl2br(e($item['content'])) ?></div>
        
        <div class="meta">
            <div class="uuid">
                <strong>UUID:</strong> <?= e($item['uuid']) ?>
            </div>
            <a href="list.php">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke List
            </a>
        </div>
    </div>
</body>
</html>