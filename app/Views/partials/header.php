<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SILUAK') ?> - SILUAK</title>
    <style>
        :root{font-family:Arial,sans-serif;color:#172033;background:#f5f7fb}*{box-sizing:border-box}body{margin:0}header{background:#123d68;color:#fff;padding:1rem 5vw;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}header a{color:#fff;text-decoration:none;margin-right:1rem}main{max-width:1180px;margin:2rem auto;padding:0 1rem}.card{background:#fff;border:1px solid #dfe5ec;border-radius:12px;padding:1rem;margin:1rem 0;box-shadow:0 2px 8px #13243b0b}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1rem}.row{display:flex;gap:.75rem;align-items:center;flex-wrap:wrap}label{display:block;font-weight:600;margin:.65rem 0 .25rem}input,select,textarea{width:100%;padding:.65rem;border:1px solid #c8d2df;border-radius:7px;background:#fff}button,.button{border:0;border-radius:7px;background:#145b91;color:#fff;padding:.65rem 1rem;cursor:pointer;text-decoration:none;display:inline-block}button.danger{background:#a43232}table{width:100%;border-collapse:collapse;background:#fff}th,td{text-align:left;padding:.65rem;border-bottom:1px solid #e6eaf0;vertical-align:top}th{background:#eef3f8}.table-wrap{overflow:auto}.muted{color:#667085}.flash{padding:.75rem 1rem;border-radius:8px;margin:.75rem 0;background:#e8f5e9}.error{background:#ffeded;color:#842029}small{display:block;color:#a43232;margin-top:.25rem}
    </style>
</head>
<body>
<header>
    <strong><a href="<?= site_url('/') ?>"><img src="<?= base_url('assets/logo.png') ?>" alt="SILUAK" style="height:34px;vertical-align:middle"></a></strong>
    <nav>
        <a href="<?= site_url('/') ?>">Beranda</a>
        <a href="<?= site_url('layanan') ?>">Layanan</a>
        <?php if (session()->get('logged_in')): ?>
            <a href="<?= site_url('dashboard') ?>">Admin</a>
            <form method="post" action="<?= site_url('logout') ?>" style="display:inline"><?= csrf_field() ?><button type="submit">Keluar</button></form>
        <?php else: ?>
            <a href="<?= site_url('login/admin') ?>">Login Admin</a>
        <?php endif ?>
    </nav>
</header>
<main>
    <?php if ($message = session()->getFlashdata('success')): ?><div class="flash"><?= esc($message) ?></div><?php endif ?>
    <?php if ($message = session()->getFlashdata('error')): ?><div class="flash error"><?= esc($message) ?></div><?php endif ?>
    <?php foreach ((array) session()->getFlashdata('errors') as $error): ?><div class="flash error"><?= esc($error) ?></div><?php endforeach ?>
