<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - SILUAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dinas-blue': '#2566A8',
                        'dinas-dark': '#1E40AF',
                    },
                },
            },
        };
    </script>
    <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24}</style>
</head>
<body class="bg-gray-50 font-sans text-slate-900">
    <div class="relative flex min-h-screen w-full flex-col">
        <div class="flex flex-1 items-center justify-center px-4 py-20">
            <div class="flex w-full max-w-[480px] flex-col items-center rounded-xl border border-gray-100 bg-white p-8 shadow-sm sm:p-12">
                <img class="h-24 w-auto" src="<?= base_url('assets/logo.png') ?>" alt="Logo Provinsi Jawa Tengah">
                <h1 class="mt-4 pb-2 text-center text-3xl font-extrabold text-gray-900">Masuk Admin</h1>

                <?php if ($message = session()->getFlashdata('error')): ?>
                    <div class="mb-6 w-full rounded-lg border border-red-200 bg-red-50 p-3 text-center text-sm font-medium text-red-700"><?= esc($message) ?></div>
                <?php endif ?>
                <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                    <div class="mb-6 w-full rounded-lg border border-red-200 bg-red-50 p-3 text-center text-sm font-medium text-red-700"><?= esc($error) ?></div>
                <?php endforeach ?>

                <form class="w-full space-y-5" action="<?= site_url('login/admin') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700" for="email">Username atau Email</label>
                        <input class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 transition-all focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20" id="email" name="email" type="text" placeholder="Masukkan email admin" value="<?= esc(old('email')) ?>" required autofocus autocomplete="username">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700" for="password">Kata Sandi</label>
                        <div class="group relative">
                            <input class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 pr-12 transition-all focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20" id="password" name="password" type="password" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                            <button class="absolute right-0 top-0 flex h-full items-center justify-center px-4 text-gray-400 transition-colors hover:text-blue-600" type="button" aria-label="Tampilkan kata sandi" onclick="togglePassword()">
                                <span class="material-symbols-outlined text-xl" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                    <button class="mt-4 flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-dinas-blue font-bold text-white shadow-sm transition-all duration-200 hover:bg-dinas-dark hover:shadow-md" type="submit">Masuk</button>
                </form>
            </div>
        </div>
        <footer class="border-t border-slate-100 bg-white py-6 text-center">
            <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-center gap-2 px-6">
                <p class="text-xs font-medium text-slate-500">&copy; <?= date('Y') ?> Dinas Kearsipan dan Perpustakaan Provinsi Jawa Tengah</p>
            </div>
        </footer>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            eyeIcon.textContent = passwordInput.type === 'password' ? 'visibility' : 'visibility_off';
        }
    </script>
</body>
</html>
