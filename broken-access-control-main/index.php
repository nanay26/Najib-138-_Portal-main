<?php
require 'config.php';
if (empty($_SESSION['user'])) header('Location: login.php');
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Dashboard — Aplikasi Keamanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    :root {
      --bg1: #fff5f0;
      --bg2: #fef2f2;
      --glass: rgba(255, 255, 255, 0.65);
      --text: #2e2e2e;
      --muted: #6b6b6b;
      --accent: #ea580c;
      --danger: #dc2626;
      --success: #16a34a;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: linear-gradient(135deg, var(--bg1), var(--bg2), #ffe4e6);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px;
      color: var(--text);
    }

    .dashboard {
      width: 100%;
      max-width: 920px;
      backdrop-filter: blur(16px) saturate(180%);
      background: var(--glass);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 16px 40px rgba(255, 136, 102, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      animation: fadeIn 0.6s ease-out;
    }

    h1 {
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      margin-bottom: 28px;
      color: var(--accent);
    }

    .areas {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-top: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.85);
      border-radius: 18px;
      padding: 26px 24px;
      border: 1px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 8px 24px rgba(255, 132, 0, 0.08);
      transition: all 0.25s ease;
      backdrop-filter: blur(12px);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .card h3 {
      font-size: 18px;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .card p {
      font-size: 14px;
      color: var(--muted);
      line-height: 1.5;
    }

    .card.vuln {
      border-left: 5px solid var(--danger);
      background: linear-gradient(135deg, rgba(254, 202, 202, 0.8), rgba(255, 255, 255, 0.8));
    }

    .card.safe {
      border-left: 5px solid var(--success);
      background: linear-gradient(135deg, rgba(220, 252, 231, 0.8), rgba(255, 255, 255, 0.8));
    }

    .btn {
      display: inline-block;
      margin-top: 16px;
      padding: 10px 16px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.25s ease;
    }

    .btn-vuln {
      background: linear-gradient(135deg, #fb7185, #dc2626);
      color: #fff;
      box-shadow: 0 8px 18px rgba(220, 38, 38, 0.18);
    }

    .btn-safe {
      background: linear-gradient(135deg, #facc15, #ea580c);
      color: #fff;
      box-shadow: 0 8px 18px rgba(234, 88, 12, 0.18);
    }

    .btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }

    .logout {
      text-align: center;
      margin-top: 32px;
    }

    .logout a {
      display: inline-block;
      color: var(--muted);
      text-decoration: none;
      font-weight: 600;
      border: 1px solid rgba(100, 116, 139, 0.3);
      padding: 10px 18px;
      border-radius: 12px;
      transition: all 0.25s ease;
    }

    .logout a:hover {
      background: rgba(234, 88, 12, 0.08);
      color: var(--accent);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media(max-width: 720px) {
      .areas {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="dashboard">
    <h1>Halo, <?= htmlspecialchars($user['username']) ?> 🔥</h1>

    <div class="areas">
      <div class="card vuln">
        <h3>⚠️ Area Rentan</h3>
        <p>Contoh <b>Broken Access Control (IDOR)</b> tanpa validasi kepemilikan data. Gunakan hanya untuk pembelajaran.</p>
        <a href="vuln/list.php" class="btn btn-vuln">Masuk Area Rentan</a>
      </div>

      <div class="card safe">
        <h3>🛡️ Area Aman</h3>
        <p>Versi aman menggunakan <b>UUID</b>, <b>token</b>, dan <b>ownership check</b> agar tidak ada kebocoran data.</p>
        <a href="safe/list.php" class="btn btn-safe">Masuk Area Aman</a>
      </div>
    </div>

    <div class="logout">
      <a href="logout.php">Keluar</a>
    </div>
  </div>
</body>

</html>
