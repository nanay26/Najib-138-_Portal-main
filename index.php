<?php
$repos = [
  ["title" => "Praktikum SQL Injection", "folder" => "praktikum_sqlinjection-main"],
  ["title" => "Praktikum XSS", "folder" => "praktikum_xss-main"],
  ["title" => "Upload Vulnerability", "folder" => "demo-app"],
  ["title" => "Broken Access Control", "folder" => "broken-access-control-main"]
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Portal Praktikum — Keamanan Data dan Informasi</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
      --card-bg: rgba(255, 255, 255, 0.08);
      --card-border: rgba(255, 255, 255, 0.12);
      --text-primary: #ffffff;
      --text-secondary: #b0b0d0;
      --accent1: #7c3aed;
      --accent2: #06b6d4;
      --accent3: #3b82f6;
      --shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      --shadow-glow: 0 0 30px rgba(124, 58, 237, 0.2);
      --radius: 20px;
    }

    * { 
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text-primary);
      min-height: 100vh;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%);
      z-index: -1;
    }

    header {
      padding: 4rem 2rem;
      background: linear-gradient(135deg, var(--accent1), var(--accent2), var(--accent3));
      background-size: 300% 300%;
      animation: gradientShift 8s ease infinite;
      color: #fff;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    h1 {
      margin: 0;
      font-size: 2.8rem;
      font-weight: 800;
      text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .authors {
      margin-top: 1rem;
      font-size: 1.1rem;
      opacity: 0.95;
      font-weight: 500;
      display: inline-flex;
      gap: 2rem;
      background: rgba(255,255,255,0.1);
      padding: 0.8rem 2rem;
      border-radius: 50px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .container {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
      text-align: center;
    }

    .container h2 {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 3rem;
      background: linear-gradient(135deg, var(--text-primary), var(--text-secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .card {
      background: var(--card-bg);
      border-radius: var(--radius);
      padding: 2.5rem;
      border: 1px solid var(--card-border);
      backdrop-filter: blur(15px);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--accent1), var(--accent2));
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: var(--shadow), var(--shadow-glow);
      border-color: rgba(124, 58, 237, 0.3);
    }

    .card:hover::before {
      transform: scaleX(1);
    }

    .card h3 {
      margin: 0;
      font-size: 1.4rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--accent1), var(--accent2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1rem;
    }

    .card p {
      margin: 0.5rem 0 0;
      color: var(--text-secondary);
      font-size: 0.95rem;
    }

    .card code {
      background: rgba(0,0,0,0.3);
      padding: 0.4rem 0.8rem;
      border-radius: 8px;
      border: 1px solid var(--card-border);
      font-family: 'Courier New', monospace;
      font-size: 0.9rem;
    }

    .btn {
      margin-top: 1.5rem;
      padding: 1rem 2rem;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.95rem;
      background: linear-gradient(135deg, var(--accent1), var(--accent2));
      color: #fff;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(124, 58, 237, 0.4);
    }

    .btn:hover::before {
      left: 100%;
    }

    /* Overlay */
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 15, 35, 0.9);
      backdrop-filter: blur(15px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      z-index: 10000;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .overlay.show {
      opacity: 1;
    }

    .sheet {
      width: min(900px, 95vw);
      max-height: 85vh;
      background: var(--card-bg);
      border-radius: var(--radius);
      border: 1px solid var(--card-border);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transform: scale(0.9);
      transition: transform 0.3s ease;
      box-shadow: var(--shadow), var(--shadow-glow);
    }

    .overlay.show .sheet {
      transform: scale(1);
    }

    .sheet-head {
      padding: 1.5rem 2rem;
      border-bottom: 1px solid var(--card-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0, 0, 0, 0.2);
    }

    .sheet-title {
      font-weight: 700;
      font-size: 1.3rem;
      color: var(--text-primary);
    }

    .sheet-body {
      padding: 2rem;
      overflow: auto;
    }

    .filegrid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.2rem;
      margin-top: 1.5rem;
    }

    .filecard {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--card-border);
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .filecard::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--accent1), var(--accent2));
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .filecard:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-5px);
      border-color: rgba(124, 58, 237, 0.3);
    }

    .filecard:hover::before {
      transform: scaleX(1);
    }

    .filecard a {
      color: var(--text-primary);
      text-decoration: none;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }

    .filemeta {
      font-size: 0.85rem;
      color: var(--text-secondary);
      margin-top: 0.8rem;
    }

    .close {
      background: transparent;
      border: 1px solid var(--card-border);
      border-radius: 10px;
      padding: 0.8rem 1.2rem;
      cursor: pointer;
      color: var(--text-secondary);
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .close:hover {
      background: var(--accent1);
      color: white;
      border-color: var(--accent1);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card {
      animation: fadeInUp 0.6s ease forwards;
      opacity: 0;
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }

    @media (max-width: 900px) {
      .container { 
        margin: 3rem auto; 
      }
      
      h1 {
        font-size: 2.2rem;
      }
      
      .authors {
        flex-direction: column;
        gap: 0.5rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Portal Keamanan Data dan Informasi</h1>
    <div class="authors">Muhammad Ainun Najib (C2C023138)</div>
  </header>

  <div class="container">
    <h2>Daftar Project Praktikum</h2>
    <div class="grid" id="cards"></div>
  </div>

  <!-- Overlay -->
  <div class="overlay" id="overlay">
    <div class="sheet">
      <div class="sheet-head">
        <div class="sheet-title" id="ov-title">Project</div>
        <button class="close" id="ov-close">Tutup</button>
      </div>
      <div class="sheet-body">
        <div id="ov-path" class="filemeta"></div>
        <div id="ov-files" class="filegrid"></div>
      </div>
    </div>
  </div>

  <script>
    const repos = <?php echo json_encode($repos); ?>;
    const cardsEl = document.getElementById('cards');
    const overlay = document.getElementById('overlay');
    const ovTitle = document.getElementById('ov-title');
    const ovPath = document.getElementById('ov-path');
    const ovFiles = document.getElementById('ov-files');
    const closeBtn = document.getElementById('ov-close');

    closeBtn.onclick = () => {
      overlay.classList.remove('show');
      setTimeout(() => overlay.style.display = 'none', 300);
    };

    function createCard(r) {
      const el = document.createElement('div');
      el.className = 'card';
      el.innerHTML = `
        <h3>${r.title}</h3>
        <p>Folder: <code>${r.folder}</code></p>
        <button class="btn" data-action="open" data-folder="${r.folder}" data-title="${r.title}">
          <i class="fas fa-folder-open"></i>
          Buka Project
        </button>
      `;
      return el;
    }

    repos.forEach(r => cardsEl.appendChild(createCard(r)));

    async function openProject(folder, title) {
      ovTitle.textContent = title;
      ovPath.textContent = `Path: ${folder}/`;
      ovFiles.innerHTML = 'Memuat daftar file...';
      
      overlay.style.display = 'flex';
      setTimeout(() => overlay.classList.add('show'), 10);

      try {
        const resp = await fetch(`dirlist.php?path=${encodeURIComponent(folder)}`);
        const data = await resp.json();
        if (!data.ok) throw new Error(data.error || 'Gagal memuat');
        const items = data.items || [];
        if (items.length === 0) {
          ovFiles.innerHTML = '<div class="filemeta">Tidak ada file di folder ini.</div>';
          return;
        }
        ovFiles.innerHTML = '';
        items.forEach(it => {
          const display = it.name.replace(/\/$/, '');
          const isDir = it.is_dir;
          const ext = display.includes('.') ? display.split('.').pop().toLowerCase() : '';
          const url = it.href;
          const card = document.createElement('div');
          card.className = 'filecard';
          const icon = isDir ? '📁' : (ext === 'php' ? '🟪' : ext === 'html' ? '🟧' : '📄');
          card.innerHTML = `
            <div>
              <span>${icon}</span> 
              <a href="${url}" target="_blank">${display}</a>
            </div>
            <div class="filemeta">${isDir ? 'Folder' : 'File .' + ext}</div>
          `;
          ovFiles.appendChild(card);
        });
      } catch (err) {
        ovFiles.innerHTML = `<div class="filemeta">Gagal memuat: ${err.message}</div>`;
      }
    }

    document.addEventListener('click', e => {
      if (e.target.dataset.action === 'open') {
        openProject(e.target.dataset.folder, e.target.dataset.title);
      }
    });
  </script>
</body>
</html>