<?= $this->include('partials/header', ['title' => 'Menu Layanan']) ?>
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Menu Layanan</h2>
    <a class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600" href="<?= site_url('admin/layanan/add') ?>">
        <span class="material-symbols-outlined">add</span>Tambah Layanan Baru
    </a>
</div>
<div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-500">Nama Layanan</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-500">Icon</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase text-slate-500">Status di Sidebar</th>
                    <th class="px-6 py-3 text-right text-xs font-bold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($menus as $menu): ?>
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-800"><?= esc($menu['nama_layanan']) ?></td>
                        <td class="px-6 py-4"><span class="material-symbols-outlined text-slate-500"><?= esc($menu['icon']) ?></span></td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold <?= $menu['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                                <?= $menu['is_active'] ? 'Ditampilkan' : 'Disembunyikan' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="<?= site_url('admin/layanan/toggle/' . $menu['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button class="text-slate-400 transition-colors hover:text-primary" type="submit" aria-label="<?= $menu['is_active'] ? 'Sembunyikan' : 'Tampilkan' ?> <?= esc($menu['nama_layanan']) ?>">
                                    <span class="material-symbols-outlined"><?= $menu['is_active'] ? 'visibility_off' : 'visibility' ?></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if ($menus === []): ?>
                    <tr><td class="px-6 py-12 text-center text-sm italic text-slate-400" colspan="4">Belum ada menu layanan.</td></tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->include('partials/footer') ?>
