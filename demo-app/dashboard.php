<?php
require 'config.php';
if (empty($_SESSION['user'])) header('Location: login.php');
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Aplikasi Keamanan</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --primary: #FF6B35;
  --secondary: #FFA271;
  --success: #16a34a;
  --danger: #dc2626;
  --bg: #f0f4ff;
  --glass: rgba(255, 255, 255, 0.85);
  --text-primary: #1e293b;
  --text-muted: #64748b;
  --radius: 20px;
  --shadow: 0 15px 40px rgba(0,0,0,0.08);
  --transition: 0.3s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  background: var(--bg);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
  color: var(--text-primary);
}

.dashboard {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
  width: 100%;
  max-width: 1200px;
}

.card {
  background: var(--glass);
  border-radius: var(--radius);
  padding: 30px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(12px) saturate(180%);
  transition: transform var(--transition), box-shadow var(--transition);
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.12);
}

.card h2, .card h3 {
  margin-bottom: 16px;
  font-weight: 700;
}

.card p {
  color: var(--text-muted);
  line-height: 1.6;
}

button, .btn {
  padding: 12px 24px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  color: #fff;
  background: var(--primary);
  box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
  transition: all var(--transition);
  margin-right: 10px;
}

button:hover, .btn:hover {
  transform: translateY(-2px);
  opacity: 0.95;
}

.card-vuln {
  border-left: 6px solid var(--danger);
  background: linear-gradient(135deg, rgba(254,202,202,0.85), var(--glass));
}

.card-safe {
  border-left: 6px solid var(--success);
  background: linear-gradient(135deg, rgba(220,252,231,0.85), var(--glass));
}

.quick-links a {
  display: block;
  margin-bottom: 8px;
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
}

.quick-links a:hover {
  text-decoration: underline;
}

.profile {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 25px;
}

.profile .avatar {
  width: 60px;
  height: 60px;
  background: var(--primary);
  color: #fff;
  font-weight: 700;
  font-size: 22px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
}

@media(max-width: 900px) {
  .dashboard {
    grid-template-columns: 1fr;
  }
}
</style>
</head>

<body>

<div class="dashboard">
  <!-- Kiri -->
  <div class="card">
    <div class="profile">
      <div class="avatar"><?= strtoupper(substr($user['username'],0,2)) ?></div>
      <div>
        <h2>Selamat datang, <?= htmlspecialchars($user['username']) ?>!</h2>
        <p>Anda masuk sebagai <b><?= htmlspecialchars($user['username']) ?></b></p>
      </div>
    </div>

    <h3>Menu Utama</h3>
    <p>Gunakan tombol di bawah untuk navigasi cepat.</p>
    <div style="margin-top: 10px;">
      <button class="btn">Buat User (Safe)</button>
      <button class="btn" style="background: #f97316;">Buat User (Vuln)</button>
      <button class="btn" style="background: #3b82f6;">Login (Safe)</button>
    </div>

    <h3 style="margin-top:30px;">Ringkasan</h3>
    <p>Ini adalah dashboard sederhana setelah login. Halaman ini hanya contoh untuk praktik keamanan web (demo).</p>
    <p style="margin-top:10px;font-size:14px;color:var(--text-muted);">Demo App — praktik keamanan web. Jangan gunakan data demo di lingkungan produksi.</p>
  </div>

  <!-- Kanan -->
  <div class="card">
    <h3>Link Cepat</h3>
    <div class="quick-links">
      <a href="#">📝 Artikel (Versi RENTAN)</a>
      <a href="#">✅ Artikel (Versi AMAN)</a>
      <a href="#">Daftar (Safe)</a>
      <a href="#">Daftar (Vul)</a>
    </div>

    <h3>Status Sesi</h3>
    <p>Sesi Anda aktif. Untuk mengakhiri sesi, gunakan tombol Logout di atas.</p>
    <button style="background: #ef4444;margin-top:15px;">Logout</button>
  </div>
</div>

</body>
</html>
