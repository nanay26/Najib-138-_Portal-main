<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username']);
  $e = trim($_POST['email']);
  $p = $_POST['password'];

  // Validasi sederhana
  if (strlen($u) < 3 || strlen($p) < 4 || !filter_var($e, FILTER_VALIDATE_EMAIL)) {
    $err = "Periksa kembali data yang kamu masukkan!";
  } else {
    // Cek apakah username atau email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
    $stmt->execute([':u' => $u, ':e' => $e]);

    if ($stmt->fetch()) {
      $err = "Username atau email sudah digunakan!";
    } else {
      // Simpan data baru
      $hashed = password_hash($p, PASSWORD_DEFAULT);
      $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)");
      $insert->execute([':u' => $u, ':e' => $e, ':p' => $hashed]);

      // Redirect ke login
      header('Location: login.php?registered=1');
      exit;
    }
  }
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Daftar Akun | Aplikasi BOS</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg1: #edf2fb;
      --bg2: #e2eafc;
      --primary: #2563eb;
      --accent: #1e40af;
      --danger: #dc2626;
      --text: #1e293b;
      --muted: #64748b;
      --white: #fff;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .card {
      width: 100%;
      max-width: 420px;
      background: var(--white);
      border-radius: 18px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.08);
      padding: 40px 36px;
      position: relative;
      overflow: hidden;
      animation: fadeIn 0.6s ease-out;
    }

    .card::before {
      content: '';
      position: absolute;
      top: -100px;
      left: -100px;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle at center, rgba(37,99,235,0.15), transparent 70%);
    }

    h2 {
      text-align: center;
      font-weight: 700;
      font-size: 22px;
      color: var(--text);
      margin-bottom: 28px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
      position: relative;
      z-index: 1;
    }

    input {
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      font-size: 15px;
      color: var(--text);
      outline: none;
      transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    button {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: var(--white);
      font-weight: 600;
      font-size: 15px;
      padding: 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(37,99,235,0.25);
    }

    .error {
      background: #fee2e2;
      color: var(--danger);
      padding: 10px 12px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      border: 1px solid rgba(220,38,38,0.2);
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: var(--muted);
    }

    .footer a {
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .footer a:hover {
      color: var(--accent);
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <div class="card">
    <h2>Buat Akun Baru 📝</h2>

    <?php if (isset($err)): ?>
      <div class="error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post">
      <input name="username" placeholder="Nama pengguna" required>
      <input name="email" type="email" placeholder="Alamat email" required>
      <input name="password" type="password" placeholder="Kata sandi" required>
      <button type="submit">Daftar</button>
    </form>

    <div class="footer">
      Sudah punya akun? <a href="login.php">Masuk di sini</a>
    </div>
  </div>
</body>
</html>
