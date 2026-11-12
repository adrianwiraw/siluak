<?= $this->include('partials/header', ['title' => 'Buat Layanan Baru']) ?>
<div class="mx-auto max-w-6xl space-y-6">
    <div>
        <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
            <a class="transition-colors hover:text-primary" href="<?= site_url('dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="font-medium text-slate-800">Buat Layanan</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Buat Layanan Baru</h2>
        <p class="mt-1 text-slate-500">Tambahkan layanan baru yang akan tersedia di aplikasi.</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6 md:p-8">
            <form action="<?= site_url('admin/layanan/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="space-y-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700" for="service_name">Nama Layanan <span class="text-red-500">*</span></label>
                        <input class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary focus:ring-primary" id="service_name" name="nama_layanan" placeholder="Contoh: Pengajuan Cuti, Laporan Keuangan" type="text" value="<?= esc(old('nama_layanan')) ?>" required>
                    </div>

                    <?php
                    $icons = [
                        'description' => 'Docs', 'assignment' => 'Task', 'article' => 'Post',
                        'draw' => 'Sign', 'history_edu' => 'Write', 'folder' => 'Folder',
                        'group' => 'People', 'person' => 'User', 'badge' => 'ID Card',
                        'calendar_month' => 'Date', 'event' => 'Event', 'schedule' => 'Time',
                        'payments' => 'Money', 'account_balance' => 'Bank', 'receipt_long' => 'Bill',
                        'inventory_2' => 'Box', 'local_shipping' => 'Truck', 'store' => 'Store',
                        'school' => 'Edu', 'auto_stories' => 'Book', 'military_tech' => 'Cert',
                        'settings' => 'Gear', 'build' => 'Fix', 'construction' => 'Tools',
                        'mail' => 'Email', 'campaign' => 'Info', 'support_agent' => 'Help',
                    ];
                    ?>
                    <fieldset class="space-y-3">
                        <legend class="block text-sm font-semibold text-slate-700">Pilih Icon <span class="text-red-500">*</span></legend>
                        <div class="grid grid-cols-4 gap-3 sm:grid-cols-6 md:grid-cols-8">
                            <?php foreach ($icons as $icon => $label): ?>
                                <div class="relative">
                                    <input class="peer sr-only" id="icon-<?= esc($icon) ?>" name="icon" type="radio" value="<?= esc($icon) ?>" <?= old('icon', 'description') === $icon ? 'checked' : '' ?>>
                                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-lg border border-slate-200 p-3 text-slate-500 transition-all hover:bg-slate-50 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary" for="icon-<?= esc($icon) ?>">
                                        <span class="material-symbols-outlined mb-1 text-2xl"><?= esc($icon) ?></span>
                                        <span class="w-full truncate text-center text-[9px] font-medium"><?= esc($label) ?></span>
                                    </label>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </fieldset>

                    <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-700">Status Aktif</span>
                            <span class="mt-0.5 text-xs text-slate-500">Tampilkan layanan ini di sidebar menu.</span>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center" for="status_toggle">
                            <input class="peer sr-only" id="status_toggle" name="status" type="checkbox" value="active" <?= old('status', 'active') === 'active' ? 'checked' : '' ?>>
                            <span class="relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-4 peer-focus:ring-primary/20"></span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <a class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50" href="<?= site_url('dashboard') ?>">Batal</a>
                    <button class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition-all hover:bg-blue-600 focus:ring-2 focus:ring-primary focus:ring-offset-2" type="submit">
                        <span class="material-symbols-outlined text-lg">save</span>Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->include('partials/footer') ?>
