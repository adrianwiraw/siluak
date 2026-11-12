<?php
$uriPath = trim(uri_string(), '/');
$firstSegment = explode('/', $uriPath)[0] ?? '';
$isAdminArea = session()->get('logged_in') && in_array($firstSegment, [
    'admin', 'atk', 'bmd', 'dashboard', 'export', 'laporan', 'manajemen',
    'manajemen-stok', 'peminjaman', 'search',
], true);
$title = $title ?? 'SILUAK';
$isActive = static fn (string $prefix): bool => $uriPath === $prefix || str_starts_with($uriPath, $prefix . '/');
$navActive = 'flex items-center gap-3 rounded-lg bg-primary px-3 py-2.5 text-white shadow-md shadow-primary/20';
$navIdle = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-600 transition-colors hover:bg-slate-100 hover:text-primary';
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> - SILUAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: false,
            theme: {
                extend: {
                    colors: {
                        primary: '#2566A8',
                        'dinas-blue': '#2566A8',
                        'dinas-dark': '#1E40AF',
                        'dinas-green': '#059669',
                        'background-light': '#f3f4f6',
                        'background-dark': '#020617',
                        'surface-light': '#ffffff',
                        'surface-dark': '#0f172a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Inter', 'sans-serif'],
                        body: ['Noto Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>
        .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24}
        details>summary{list-style:none}
        details>summary::-webkit-details-marker{display:none}
    </style>
</head>
<body class="<?= $isAdminArea ? 'admin-page flex h-screen overflow-hidden bg-background-light font-display text-slate-800 antialiased' : 'public-page flex min-h-screen flex-col bg-gray-50 font-sans text-gray-800 antialiased' ?>">
<?php if ($isAdminArea): ?>
    <div class="flex h-full w-full overflow-hidden">
        <aside class="hidden h-full w-64 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
            <div class="border-b border-slate-200 px-4 pb-6 pt-5">
                <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50 p-4">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500">
                        <span class="material-symbols-outlined text-2xl">person</span>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900"><?= esc(session()->get('user_name') ?? 'Administrator') ?></p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <a class="<?= $isActive('dashboard') ? $navActive : $navIdle ?>" href="<?= site_url('dashboard') ?>">
                    <span class="material-symbols-outlined">dashboard</span><span class="text-sm font-medium">Dashboard</span>
                </a>
                <a class="<?= $isActive('bmd') ? $navActive : $navIdle ?>" href="<?= site_url('bmd') ?>">
                    <span class="material-symbols-outlined">inventory_2</span><span class="text-sm font-medium">Kebutuhan BMD</span>
                </a>
                <details class="group" <?= $isActive('laporan') ? 'open' : '' ?>>
                    <summary class="<?= $isActive('laporan') ? $navActive : $navIdle ?> cursor-pointer select-none justify-between">
                        <span class="flex items-center gap-3"><span class="material-symbols-outlined">assignment</span><span class="text-sm font-medium">Laporan</span></span>
                        <span class="material-symbols-outlined text-xl transition-transform group-open:rotate-180">expand_more</span>
                    </summary>
                    <div class="ml-11 mt-1 space-y-1">
                        <a class="block py-1.5 text-sm font-medium <?= $isActive('laporan/peralatan') ? 'text-primary' : 'text-slate-500 hover:text-primary' ?>" href="<?= site_url('laporan/peralatan') ?>">Kerusakan Peralatan</a>
                        <a class="block py-1.5 text-sm font-medium <?= $isActive('laporan/gedung') ? 'text-primary' : 'text-slate-500 hover:text-primary' ?>" href="<?= site_url('laporan/gedung') ?>">Kerusakan Gedung</a>
                    </div>
                </details>
                <details class="group" <?= $isActive('peminjaman') ? 'open' : '' ?>>
                    <summary class="<?= $isActive('peminjaman') ? $navActive : $navIdle ?> cursor-pointer select-none justify-between">
                        <span class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span class="text-sm font-medium">Peminjaman</span></span>
                        <span class="material-symbols-outlined text-xl transition-transform group-open:rotate-180">expand_more</span>
                    </summary>
                    <div class="ml-11 mt-1 space-y-1">
                        <a class="block py-1.5 text-sm font-medium <?= $isActive('peminjaman/kendaraan') ? 'text-primary' : 'text-slate-500 hover:text-primary' ?>" href="<?= site_url('peminjaman/kendaraan') ?>">Kendaraan Dinas</a>
                        <a class="block py-1.5 text-sm font-medium <?= $isActive('peminjaman/ruang') ? 'text-primary' : 'text-slate-500 hover:text-primary' ?>" href="<?= site_url('peminjaman/ruang') ?>">Ruangan Rapat</a>
                    </div>
                </details>
                <a class="<?= $isActive('atk') ? $navActive : $navIdle ?>" href="<?= site_url('atk') ?>">
                    <span class="material-symbols-outlined">school</span><span class="text-sm font-medium">Permohonan ATK</span>
                </a>
                <a class="<?= $isActive('manajemen') ? $navActive : $navIdle ?>" href="<?= site_url('manajemen/stok') ?>">
                    <span class="material-symbols-outlined">inventory</span><span class="text-sm font-medium">Manajemen Stok</span>
                </a>
                <a class="<?= $isActive('admin/layanan') ? $navActive : $navIdle ?>" href="<?= site_url('admin/layanan/index') ?>">
                    <span class="material-symbols-outlined">widgets</span><span class="text-sm font-medium">Menu Layanan</span>
                </a>
            </nav>
            <div class="mt-auto border-t border-slate-200 p-4">
                <form method="post" action="<?= site_url('logout') ?>">
                    <?= csrf_field() ?>
                    <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-slate-600 transition-colors hover:bg-red-50 hover:text-red-600" type="submit">
                        <span class="material-symbols-outlined">logout</span><span class="text-sm font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        <div class="relative flex h-full min-w-0 flex-1 flex-col overflow-hidden">
            <header class="z-10 flex shrink-0 items-center justify-between border-b border-slate-200 bg-white px-6 py-3">
                <div class="flex items-center gap-4 lg:hidden">
                    <button class="text-slate-500" type="button" aria-label="Buka navigasi">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
                <div class="ml-4 w-full max-w-xl lg:ml-0">
                    <form action="<?= site_url('search') ?>" method="get" class="relative">
                        <span class="material-symbols-outlined pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">search</span>
                        <input class="block w-full rounded-lg border-0 bg-slate-100 py-2 pl-10 pr-3 leading-5 text-slate-900 placeholder-slate-500 focus:ring-2 focus:ring-primary/50 sm:text-sm" name="q" placeholder="Cari layanan, atau laporan..." type="text" value="<?= esc(service('request')->getGet('q') ?? '') ?>">
                    </form>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
                <div class="mx-auto max-w-7xl space-y-6">
<?php else: ?>
    <nav class="sticky top-0 z-50 flex items-center justify-between border-b border-gray-100 bg-white px-6 py-4 shadow-sm md:px-12">
        <div class="flex items-center gap-4">
            <img class="h-12 w-auto" src="<?= base_url('assets/logo.png') ?>" alt="Logo Provinsi Jawa Tengah">
            <div class="leading-tight">
                <h1 class="text-lg font-bold text-gray-800"><a class="transition hover:text-blue-600" href="<?= site_url('/') ?>">Subbag Umum dan Kepegawaian</a></h1>
                <p class="text-xs font-medium text-gray-500">Dinas Kearsipan dan Perpustakaan Provinsi Jawa Tengah</p>
            </div>
        </div>
        <div class="hidden items-center gap-8 text-sm font-medium text-gray-600 md:flex">
            <a class="transition hover:text-blue-600" href="<?= site_url('/') ?>#layanan">Layanan</a>
            <a class="transition hover:text-blue-600" href="<?= site_url('/') ?>#jadwal">Jadwal</a>
            <a class="transition hover:text-blue-600" href="<?= site_url('tiket') ?>">Cek Tiket</a>
            <div class="group relative flex h-full items-center">
                <button class="flex items-center gap-1 py-2 transition hover:text-blue-600 focus:outline-none" type="button">Laporan</button>
                <div class="invisible absolute right-0 top-full z-50 mt-0 w-64 overflow-hidden rounded-lg border border-slate-200 bg-white opacity-0 shadow-xl transition-all duration-300 group-hover:visible group-hover:opacity-100">
                    <div class="flex flex-col py-1">
                        <?php foreach ([
                            'bmd' => 'Rencana Kebutuhan BMD',
                            'kerusakan_alat' => 'Kerusakan Peralatan Kantor',
                            'kerusakan_gedung' => 'Kerusakan Gedung & Bangunan',
                            'pinjam_kendaraan' => 'Peminjaman Kendaraan Dinas',
                            'pinjam_ruangan' => 'Peminjaman Ruangan Rapat',
                            'atk' => 'Permohonan ATK',
                        ] as $report => $label): ?>
                            <a class="px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-blue-50 hover:text-blue-600" href="<?= site_url('umpeg/laporan/' . $report) ?>"><?= esc($label) ?></a>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main class="w-full flex-grow bg-gray-50">
<?php endif ?>
    <?php foreach (['success' => ['emerald', 'check_circle'], 'error' => ['red', 'error']] as $flashKey => [$tone, $icon]): ?>
        <?php if ($message = session()->getFlashdata($flashKey)): ?>
            <div class="fixed left-1/2 top-5 z-[9999] w-full max-w-md -translate-x-1/2 px-4" id="flash-<?= esc($flashKey) ?>">
                <div class="mx-4 flex items-center justify-between rounded-2xl border border-<?= esc($tone) ?>-400 bg-<?= esc($tone) ?>-500 p-4 text-white shadow-2xl shadow-<?= esc($tone) ?>-500/30">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined rounded-xl bg-white/20 p-2 text-2xl font-bold"><?= esc($icon) ?></span>
                        <div>
                            <p class="mb-1 text-[10px] font-black uppercase leading-none tracking-widest opacity-80"><?= $flashKey === 'success' ? 'Berhasil' : 'Perhatian' ?></p>
                            <p class="text-sm font-bold"><?= esc($message) ?></p>
                        </div>
                    </div>
                    <button class="rounded-lg p-1 transition-colors hover:bg-white/10" type="button" aria-label="Tutup notifikasi" onclick="document.getElementById('flash-<?= esc($flashKey) ?>').remove()">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
            </div>
        <?php endif ?>
    <?php endforeach ?>
    <?php $formErrors = (array) session()->getFlashdata('errors'); ?>
    <?php if ($formErrors !== []): ?>
        <div class="fixed left-1/2 top-5 z-[9999] w-full max-w-md -translate-x-1/2 px-4" id="flash-validation">
            <div class="mx-4 rounded-2xl border border-red-400 bg-red-500 p-4 text-sm font-bold text-white shadow-2xl shadow-red-500/30">
                <ul class="list-inside list-disc"><?php foreach ($formErrors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
            </div>
        </div>
    <?php endif ?>
    <script>
        document.querySelectorAll('[id^="flash-"]').forEach((alert) => {
            window.setTimeout(() => {
                alert.style.transition = 'opacity .5s ease, transform .5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translate(-50%, -20px)';
                window.setTimeout(() => alert.remove(), 500);
            }, 4000);
        });
    </script>
