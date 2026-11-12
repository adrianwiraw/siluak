<?= $this->include('partials/header', ['title' => $item ? 'Ubah Stok' : 'Tambah Stok Baru']) ?>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
    <section class="w-full max-w-2xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-8 py-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800"><?= $item ? 'Ubah Stok Barang' : 'Tambah Stok Baru' ?></h2>
                <p class="text-sm text-slate-500"><?= $item ? 'Perbarui informasi barang di gudang.' : 'Silakan isi formulir di bawah ini untuk menambah stok gudang.' ?></p>
            </div>
            <a class="rounded-full p-2 text-slate-400 hover:bg-slate-100" href="<?= site_url('manajemen/stok') ?>" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </a>
        </div>
        <form action="<?= site_url($item ? 'manajemen/update/' . $item['id'] : 'manajemen/store') ?>" method="post" class="space-y-2 p-8">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <label class="space-y-2 text-sm font-semibold text-slate-700 md:col-span-2">
                    Nama Barang
                    <input class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-normal focus:border-primary focus:ring-primary" name="nama_barang" value="<?= esc(old('nama_barang', $item['nama_barang'] ?? '')) ?>" placeholder="Ketik nama barang..." required>
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-700">
                    Kategori
                    <?php $category = old('kategori', $item['kategori'] ?? 'Kertas'); ?>
                    <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-normal focus:ring-primary" name="kategori" required>
                        <?php foreach (['Kertas', 'Alat Tulis', 'Arsip', 'Kebersihan', 'Lain-lain'] as $option): ?><option value="<?= esc($option) ?>" <?= $category === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach ?>
                    </select>
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-700">
                    Satuan
                    <?php $unit = old('satuan', $item['satuan'] ?? 'Rim'); ?>
                    <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-normal focus:ring-primary" name="satuan" required>
                        <?php foreach (['Rim', 'Pcs', 'Lusin', 'Unit', 'Pak'] as $option): ?><option value="<?= esc($option) ?>" <?= $unit === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach ?>
                    </select>
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-700">
                    <?= $item ? 'Jumlah Stok' : 'Jumlah Stok Masuk' ?>
                    <input class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-normal focus:ring-primary" type="number" min="0" name="stok" value="<?= esc(old('stok', $item['stok'] ?? '')) ?>" placeholder="0" required>
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-700">
                    Stok Minimal
                    <input class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-normal focus:ring-primary" type="number" min="0" name="stok_minimal" value="<?= esc(old('stok_minimal', $item['stok_minimal'] ?? 0)) ?>" placeholder="0" required>
                </label>
                <?php if (! $item): ?>
                    <label class="space-y-2 text-sm font-semibold text-slate-700 md:col-span-2">
                        Tanggal Masuk
                        <input class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500" type="date" value="<?= esc(date('Y-m-d')) ?>" readonly>
                    </label>
                <?php endif ?>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a class="rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition-all hover:bg-slate-50" href="<?= site_url('manajemen/stok') ?>">Batal</a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-primary px-8 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-blue-700" type="submit">
                    <span class="material-symbols-outlined text-sm">save</span><?= $item ? 'Simpan Perubahan' : 'Simpan Stok' ?>
                </button>
            </div>
        </form>
    </section>
</div>
<?= $this->include('partials/footer') ?>
