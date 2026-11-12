<?= $this->include('partials/header', ['title' => 'Manajemen Stok']) ?>
<?php
$categories = array_values(array_unique(array_filter(array_map(static fn (array $item): string => (string) ($item['kategori'] ?? ''), $items))));
sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
?>
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Stok Gudang ATK</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola dan pantau ketersediaan stok alat tulis kantor secara real-time.</p>
    </div>
    <div class="flex gap-3">
        <a class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50" href="<?= site_url('manajemen/export-excel') ?>">
            <span class="material-symbols-outlined text-lg">download</span>Export Excel
        </a>
        <a class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/20 transition-colors hover:bg-blue-600" href="<?= site_url('manajemen/create') ?>">
            <span class="material-symbols-outlined text-lg">add</span>Tambah Barang
        </a>
    </div>
</div>

<div class="mb-8 grid grid-cols-1 items-stretch gap-6 md:grid-cols-3">
    <section class="flex min-h-[180px] flex-col justify-between rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div><p class="text-[15px] font-medium text-slate-500">Total Jenis Barang</p><h3 id="statTotalJenis" class="mt-3 text-4xl font-bold text-slate-900"><?= esc($totalTypes) ?></h3></div>
            <div class="rounded-2xl bg-blue-50 p-3"><span class="material-symbols-outlined text-2xl text-primary">inventory_2</span></div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-primary"><span class="material-symbols-outlined text-base">insert_chart</span>Aktif di gudang</div>
    </section>
    <section class="flex min-h-[180px] flex-col justify-between rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div><p class="text-[15px] font-medium text-slate-500">Stok Menipis</p><h3 id="statStokMenipis" class="mt-3 text-4xl font-bold text-orange-500"><?= esc($lowStock) ?></h3></div>
            <div class="rounded-2xl bg-orange-50 p-3"><span class="material-symbols-outlined text-2xl text-orange-500">notifications_active</span></div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-orange-500"><span class="material-symbols-outlined text-base">error_outline</span>Perlu restock segera</div>
    </section>
    <section class="flex min-h-[180px] flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="mb-2 flex items-start justify-between">
            <div class="flex-1">
                <p class="text-[15px] font-medium text-slate-500">Total Keseluruhan Unit</p>
                <div class="mt-3 flex items-baseline gap-2"><h3 id="statTotalUnit" class="text-4xl font-bold text-slate-900"><?= esc(number_format((float) $totalUnits, 0, ',', '.')) ?></h3><span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Unit</span></div>
            </div>
            <div class="shrink-0 rounded-2xl bg-emerald-50 p-3"><span class="material-symbols-outlined text-2xl text-emerald-500">update</span></div>
        </div>
        <div class="mt-auto border-t border-slate-50 pt-4">
            <select id="itemStatFilter" class="w-full cursor-pointer rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-600 focus:ring-2 focus:ring-primary/20">
                <option value="all">Semua Barang</option>
                <?php foreach ($items as $item): ?><option value="<?= esc($item['id']) ?>"><?= esc($item['nama_barang']) ?></option><?php endforeach ?>
            </select>
        </div>
    </section>
</div>

<div class="mb-6 flex flex-col items-start justify-between gap-4 lg:flex-row lg:items-center">
    <div class="flex flex-wrap items-center gap-3">
        <select id="stokFilter" class="min-w-[150px] cursor-pointer rounded-xl border-0 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-primary/20">
            <option value="">Semua Stok</option><option value="Menipis">Stok Menipis</option><option value="Tersedia">Stok Tersedia</option>
        </select>
        <select id="kategoriFilter" class="min-w-[170px] cursor-pointer rounded-xl border-0 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-primary/20">
            <option value="">Filter Kategori</option>
            <?php foreach ($categories as $category): ?><option value="<?= esc($category) ?>"><?= esc($category) ?></option><?php endforeach ?>
        </select>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Urutkan:</span>
        <select id="sortStok" class="min-w-[160px] cursor-pointer rounded-xl border-0 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 focus:ring-2 focus:ring-primary/20">
            <option value="default">Nama Barang</option><option value="asc">Stok Terendah</option><option value="desc">Stok Tertinggi</option>
        </select>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/50">
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Nama Barang</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-slate-400">Satuan</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-widest text-slate-400">Sisa Stok</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-widest text-slate-400">Kategori</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-widest text-slate-400">Peringatan</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-widest text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody id="inventoryTableBody" class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                    <?php $low = (int) $item['stok'] <= (int) $item['stok_minimal']; ?>
                    <tr class="item-row transition-colors hover:bg-slate-50/50" data-id="<?= esc($item['id']) ?>" data-category="<?= esc(strtoupper($item['kategori'])) ?>" data-stok="<?= esc($item['stok']) ?>" data-stok-status="<?= $low ? 'Menipis' : 'Tersedia' ?>">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="flex size-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><span class="material-symbols-outlined text-xl">description</span></div>
                                <span class="text-sm font-semibold text-slate-700"><?= esc($item['nama_barang']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500"><?= esc($item['satuan']) ?></td>
                        <td class="px-6 py-5 text-center"><span class="text-sm font-bold <?= $low ? 'text-red-500' : 'text-emerald-500' ?>"><?= esc($item['stok']) ?></span></td>
                        <td class="px-6 py-5 text-center"><span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-bold uppercase tracking-tighter text-slate-600"><?= esc($item['kategori']) ?></span></td>
                        <td class="px-6 py-5 text-center">
                            <?php if ($low): ?><span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-[10px] font-bold uppercase tracking-tight text-red-600"><span class="material-symbols-outlined text-sm">report</span>Stok Menipis</span><?php else: ?><span class="text-slate-300">—</span><?php endif ?>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a class="p-2 text-slate-400 transition-all hover:text-primary" href="<?= site_url('manajemen/detail/' . $item['id']) ?>" aria-label="Ubah stok"><span class="material-symbols-outlined text-xl">edit_square</span></a>
                                <form action="<?= site_url('manajemen/destroy/' . $item['id']) ?>" method="post" onsubmit="return confirm('Hapus barang ini?')">
                                    <?= csrf_field() ?><input name="_method" type="hidden" value="DELETE">
                                    <button class="p-2 text-slate-400 transition-all hover:text-red-500" type="submit" aria-label="Hapus stok"><span class="material-symbols-outlined text-xl">delete</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr id="inventoryEmpty" <?= $items === [] ? '' : 'class="hidden"' ?>><td class="px-6 py-12 text-center text-sm italic text-slate-400" colspan="6">Belum ada data barang.</td></tr>
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const body = document.getElementById('inventoryTableBody');
    const rows = [...body.querySelectorAll('.item-row')];
    const stockFilter = document.getElementById('stokFilter');
    const categoryFilter = document.getElementById('kategoriFilter');
    const sort = document.getElementById('sortStok');
    const statFilter = document.getElementById('itemStatFilter');
    const empty = document.getElementById('inventoryEmpty');
    const totalTypes = document.getElementById('statTotalJenis');
    const lowStock = document.getElementById('statStokMenipis');
    const totalUnits = document.getElementById('statTotalUnit');
    const update = () => {
        const visible = [];
        rows.forEach((row) => {
            const matches = (!stockFilter.value || row.dataset.stokStatus === stockFilter.value)
                && (!categoryFilter.value || row.dataset.category.includes(categoryFilter.value.toLocaleUpperCase()));
            row.classList.toggle('hidden', !matches);
            if (matches) visible.push(row);
        });
        if (sort.value !== 'default') {
            visible.sort((a, b) => Number(a.dataset.stok) - Number(b.dataset.stok));
            if (sort.value === 'desc') visible.reverse();
            visible.forEach((row) => body.insertBefore(row, empty));
        }
        const lowCount = visible.filter((row) => row.dataset.stokStatus === 'Menipis').length;
        const units = visible.reduce((sum, row) => {
            return sum + (statFilter.value === 'all' || statFilter.value === row.dataset.id ? Number(row.dataset.stok) : 0);
        }, 0);
        totalTypes.textContent = visible.length;
        lowStock.textContent = lowCount;
        totalUnits.textContent = new Intl.NumberFormat('id-ID').format(units);
        empty.classList.toggle('hidden', visible.length > 0);
    };
    [stockFilter, categoryFilter, sort, statFilter].forEach((input) => input.addEventListener('change', update));
});
</script>
<?= $this->include('partials/footer') ?>
