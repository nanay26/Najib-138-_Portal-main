<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login — Secure App</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 12px 40px rgba(23, 92, 70, 0.08);
            border: 1px solid var(--border);
        }
        .brand {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
        }
        .brand p {
            color: var(--muted);
            font-size: 14px;
        }
        .msg {
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            padding: 14px 16px;
            border-radius: 12px;
        }
        .msg.error {
            color: var(--danger);
            background: #fef2f2;
            border: 1px solid rgba(220, 38, 38, 0.1);
            border-left: 4px solid var(--danger);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        label {
            font-weight: 600;
            font-size: 14px;
            color: var(--text);
        }
        input[type="text"],
        input[type="password"] {
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
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            background: linear-gradient(135deg, var(--accent), #238c6c);
            color: white;
            box-shadow: 0 4px 12px rgba(45, 157, 120, 0.2);
            margin-top: 8px;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(45, 157, 120, 0.3);
        }
        .link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--muted);
        }
        .link a {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        @media(max-width:480px) {
            .card { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <h1>Secure App</h1>
            <p>Login untuk mengakses dashboard</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required placeholder="Masukkan username">
            </div>
            
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>