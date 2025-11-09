<?php
// safe/create.php
require_once __DIR__ . '/../config.php';
if (empty($_SESSION['user'])) header('Location: ../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('CSRF fail');
    }
    
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if ($title === '') {
        $err = "Title required";
    }
    
    if (empty($err)) {
        $uuid = uuid4();
        $token = token_generate();
        $hash = token_hash($token);
        
        $stmt = $pdo->prepare("INSERT INTO items_safe (uuid, token_hash, token_expires_at, user_id, title, content) VALUES (:uuid, :th, NULL, :uid, :t, :c)");
        $stmt->execute([
            ':uuid' => $uuid,
            ':th' => $hash,
            ':uid' => $_SESSION['user']['id'],
            ':t' => $title,
            ':c' => $content
        ]);
        
        $created_uuid = $uuid;
        $created_token = $token;
    }
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Create SAFE Item</title>
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
        .msg-err {
            margin-bottom: 20px;
            background: #fef2f2;
            color: #dc2626;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(220, 38, 38, 0.1);
            font-weight: 600;
            border-left: 4px solid #dc2626;
        }
        .msg-ok {
            margin-bottom: 20px;
            background: #f0fdf9;
            color: #065f46;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid rgba(6, 95, 70, 0.1);
            font-weight: 600;
            border-left: 4px solid var(--success);
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
        .token-box {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid var(--border);
            margin-top: 8px;
            font-family: 'Monaco', 'Consolas', monospace;
            color: var(--text);
            font-size: 14px;
            word-break: break-all;
        }
        .small {
            margin-top: 16px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
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
        <div class="card" role="main" aria-labelledby="pageTitle">
            <header>
                <div>
                    <h1 id="pageTitle">Create SAFE Item</h1>
                    <div class="sub">UUID + one-time access token will be generated. Save the token once — it is shown only now.</div>
                </div>
                <div>
                    <a href="list.php" class="btn btn-ghost" style="text-decoration:none;">← Back to list</a>
                </div>
            </header>

            <?php if (!empty($err)): ?>
                <div class="msg-err"><?= e($err) ?></div>
            <?php endif; ?>

            <?php if (!empty($created_uuid) && !empty($created_token)): ?>
                <div class="msg-ok">Item berhasil dibuat. SIMPAN token akses di tempat yang aman — token hanya ditampilkan sekali.</div>
                
                <div>
                    <label>UUID</label>
                    <div class="token-box"><?= e($created_uuid) ?></div>
                </div>
                
                <div>
                    <label>ACCESS TOKEN (save this now)</label>
                    <div class="token-box"><?= e($created_token) ?></div>
                </div>
                
                <div class="small">
                    <strong>Perhatian:</strong> Token hanya ditampilkan sekali. Jika hilang, buat item baru atau implementasikan mekanisme reset token.
                </div>
                
                <div style="margin-top:20px;">
                    <a href="list.php" class="btn btn-ghost" style="text-decoration:none;">Kembali ke List</a>
                </div>
            <?php else: ?>
                <form method="post" novalidate>
                    <div>
                        <label for="title">Title</label>
                        <input id="title" name="title" type="text" placeholder="Masukkan judul" value="<?= e($_POST['title'] ?? '') ?>">
                    </div>
                    
                    <div>
                        <label for="content">Content</label>
                        <textarea id="content" name="content" placeholder="Masukkan konten"><?= e($_POST['content'] ?? '') ?></textarea>
                    </div>
                    
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    
                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Create Item</button>
                        <a href="list.php" class="btn btn-ghost">Cancel</a>
                    </div>
                    
                    <div class="small">
                        Judul wajib diisi. UUID & token akan dibuat otomatis untuk mencegah IDOR — token hanya ditampilkan sekali.
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>