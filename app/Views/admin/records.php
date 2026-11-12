<?= $this->include('partials/header', ['title' => $title]) ?>
<?php
$pages = [
    'bmd' => ['title' => 'Daftar Kebutuhan BMD', 'subtitle' => 'Kelola usulan pengadaan Barang Milik Daerah (BMD).', 'export' => 'bmd/export-excel'],
    'peralatan' => ['title' => 'Laporan Kerusakan Peralatan', 'subtitle' => 'Kelola dan pantau perbaikan aset kantor.', 'export' => 'laporan/peralatan/export-excel'],
    'gedung' => ['title' => 'Laporan Kerusakan Gedung', 'subtitle' => 'Kelola dan pantau perbaikan gedung dan bangunan.', 'export' => 'laporan/gedung/export-excel'],
    'kendaraan' => ['title' => 'Peminjaman Kendaraan Dinas', 'subtitle' => 'Kelola permohonan penggunaan kendaraan operasional.', 'export' => 'peminjaman/kendaraan/export-kendaraan'],
    'ruangan' => ['title' => 'Peminjaman Ruangan Rapat', 'subtitle' => 'Kelola reservasi ruang pertemuan dan aula kantor.', 'export' => 'peminjaman/ruang/export-ruang'],
    'atk' => ['title' => 'Permohonan ATK', 'subtitle' => 'Kelola permohonan alat tulis kantor untuk kebutuhan operasional.', 'export' => 'atk/export-excel'],
];
$page = $pages[$type] ?? ['title' => $title, 'subtitle' => '', 'export' => 'export/' . $type];
$requestedItems = static function (array $item): array {
    $decoded = json_decode((string) ($item['items'] ?? '[]'), true);
    return is_array($decoded) ? $decoded : [];
};
$formatDate = static function (?string $value, string $format = 'd M Y'): string {
    if ($value === null || $value === '') {
        return '-';
    }
    $timestamp = strtotime($value);
    return $timestamp === false ? '-' : date($format, $timestamp);
};
$statusClasses = [
    'Menunggu' => 'bg-orange-100 text-orange-600',
    'Disetujui' => 'bg-blue-100 text-blue-600',
    'Proses' => 'bg-indigo-100 text-indigo-600',
    'Selesai' => 'bg-emerald-100 text-emerald-600',
    'Ditolak' => 'bg-red-100 text-red-600',
];
$detailBase = match ($type) {
    'peralatan', 'gedung' => 'laporan/' . $type,
    'kendaraan' => 'peminjaman/kendaraan',
    'ruangan' => 'peminjaman/ruang',
    default => $type,
};
$updateBase = match ($type) {
    'peralatan', 'gedung' => 'laporan/' . $type . '/update',
    'bmd' => 'bmd/update',
    default => 'admin/update-status/' . $type,
};
?>
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900"><?= esc($page['title']) ?></h2>
        <p class="mt-1 text-slate-500"><?= esc($page['subtitle']) ?></p>
    </div>
    <div class="flex gap-3">
        <form action="<?= site_url($page['export']) ?>" method="post">
            <?= csrf_field() ?>
            <button class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50" type="submit">
                <span class="material-symbols-outlined text-lg">download</span>Export Excel
            </button>
        </form>
    </div>
</div>

<?php if (in_array($type, ['peralatan', 'gedung'], true)): ?>
    <details class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <summary class="cursor-pointer list-none px-6 py-4 font-semibold text-slate-800">
            <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-primary">add_circle</span>Tambah laporan</span>
        </summary>
        <div class="border-t border-slate-100 p-6">
            <?php if ($type === 'peralatan'): ?>
                <form class="grid grid-cols-1 gap-4 md:grid-cols-2" method="post" action="<?= site_url('laporan/peralatan/store') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="status" value="Menunggu">
                    <?php foreach (['nama' => 'Nama Pelapor', 'bidang' => 'Bidang', 'nama_alat' => 'Nama Barang', 'jenis_alat' => 'Jenis Alat'] as $field => $label): ?>
                        <label class="text-sm font-semibold text-slate-700"><?= esc($label) ?><input class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="<?= esc($field) ?>" required></label>
                    <?php endforeach ?>
                    <label class="text-sm font-semibold text-slate-700 md:col-span-2">Deskripsi Kerusakan<textarea class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="kerusakan" rows="2" required></textarea></label>
                    <label class="text-sm font-semibold text-slate-700">Prioritas<select class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="prioritas" required><option value="">Pilih prioritas</option><?php foreach (['Tinggi', 'Sedang', 'Rendah'] as $priority): ?><option><?= esc($priority) ?></option><?php endforeach ?></select></label>
                    <div class="flex items-end"><button class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Simpan laporan</button></div>
                </form>
            <?php else: ?>
                <form class="grid grid-cols-1 gap-4 md:grid-cols-2" method="post" action="<?= site_url('laporan/gedung/store') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php foreach (['nama' => 'Nama Pelapor', 'telepon' => 'No. Telepon', 'bidang' => 'Bidang', 'gedung' => 'Nama Gedung', 'lokasi' => 'Lokasi'] as $field => $label): ?>
                        <label class="text-sm font-semibold text-slate-700"><?= esc($label) ?><input class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="<?= esc($field) ?>" required></label>
                    <?php endforeach ?>
                    <label class="text-sm font-semibold text-slate-700 md:col-span-2">Deskripsi Kerusakan<textarea class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="kerusakan" rows="2" required></textarea></label>
                    <label class="text-sm font-semibold text-slate-700">Prioritas<select class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="prioritas" required><option value="">Pilih prioritas</option><?php foreach (['Tinggi', 'Sedang', 'Rendah'] as $priority): ?><option><?= esc($priority) ?></option><?php endforeach ?></select></label>
                    <label class="text-sm font-semibold text-slate-700">Foto<input class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-normal" name="lampiran" type="file" accept="image/jpeg,image/png"></label>
                    <div class="md:col-span-2"><button class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Simpan laporan</button></div>
                </form>
            <?php endif ?>
        </div>
    </details>
<?php endif ?>

<div class="mt-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
        <label class="relative w-full lg:w-96">
            <span class="material-symbols-outlined pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xl text-slate-400">search</span>
            <input id="recordSearch" class="block w-full rounded-lg border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/50" placeholder="Cari nama, bidang, atau layanan..." type="search">
        </label>
        <div class="flex flex-wrap gap-3">
            <select id="recordStatus" class="min-w-40 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-primary/50">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu', 'Disetujui', 'Proses', 'Selesai', 'Ditolak'] as $statusOption): ?><option value="<?= esc($statusOption) ?>"><?= esc($statusOption) ?></option><?php endforeach ?>
            </select>
            <select id="recordBidang" class="min-w-40 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-primary/50">
                <option value="">Semua Bidang</option>
                <?php
                $units = array_unique(array_filter(array_map(static fn (array $item): string => (string) ($item['bidang'] ?? ''), $items)));
                sort($units, SORT_NATURAL | SORT_FLAG_CASE);
                ?>
                <?php foreach ($units as $unit): ?><option value="<?= esc($unit) ?>"><?= esc($unit) ?></option><?php endforeach ?>
            </select>
        </div>
    </div>
</div>

<div class="mt-6 flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <?php
                    $headers = match ($type) {
                        'bmd' => ['Nama Pemohon', 'Bidang', 'Program / Kegiatan', 'Status', 'Tanggal Pengajuan', 'Aksi'],
                        'peralatan' => ['Nama Pelapor', 'Bidang/Seksi', 'Nama Barang', 'Jenis Alat', 'Kerusakan', 'Status', 'Prioritas', 'Aksi'],
                        'gedung' => ['Nama Pelapor', 'Bidang', 'Gedung / Lokasi', 'Deskripsi', 'Foto', 'Status', 'Prioritas', 'Aksi'],
                        'kendaraan' => ['Nama Peminjam', 'Bidang', 'Kendaraan', 'Tujuan', 'Waktu Pinjam', 'Status', 'Aksi'],
                        'ruangan' => ['Nama Peminjam', 'Bidang', 'Ruangan', 'Acara', 'Tanggal', 'Waktu', 'Status', 'Aksi'],
                        'atk' => ['Pemohon', 'Bidang', 'Barang', 'Jumlah', 'Diberikan', 'Foto', 'Status', 'Aksi'],
                        default => ['Nama Pemohon', 'Bidang', 'Detail Barang', 'Status', 'Aksi'],
                    };
                    ?>
                    <?php foreach ($headers as $header): ?><th class="whitespace-nowrap px-6 py-4"><?= esc($header) ?></th><?php endforeach ?>
                </tr>
            </thead>
            <tbody id="recordRows" class="divide-y divide-slate-100 text-sm">
                <?php foreach ($items as $item): ?>
                    <?php
                    $status = (string) ($item['status'] ?? '');
                    $requested = $requestedItems($item);
                    $searchText = implode(' ', array_map(static fn ($value): string => is_scalar($value) ? (string) $value : '', $item));
                    $priority = (string) ($item['prioritas'] ?? '');
                    $priorityClass = match ($priority) {
                        'Tinggi' => 'bg-red-100 text-red-700',
                        'Sedang' => 'bg-yellow-100 text-yellow-700',
                        'Rendah' => 'bg-green-100 text-green-700',
                        default => 'text-slate-400',
                    };
                    ?>
                    <tr class="record-row text-slate-700 transition-colors hover:bg-slate-50" data-search="<?= esc(strtolower($searchText)) ?>" data-status="<?= esc($status) ?>" data-bidang="<?= esc($item['bidang'] ?? '') ?>">
                        <?php if ($type === 'bmd'): ?>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="block font-semibold text-slate-900"><?= esc($item['nama'] ?? '-') ?></span>
                                <span class="text-xs text-slate-500"><?= esc($item['email'] ?? '') ?></span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="px-6 py-4"><span class="block font-medium text-slate-900"><?= esc($item['program'] ?? '-') ?></span><span class="text-xs italic text-slate-500"><?= esc($item['kegiatan'] ?? '-') ?></span></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium <?= esc($statusClasses[$status] ?? 'bg-slate-100 text-slate-600') ?>"><?= esc($status ?: '-') ?></span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><?= esc($formatDate($item['created_at'] ?? null)) ?></td>
                        <?php elseif ($type === 'peralatan'): ?>
                            <td class="whitespace-nowrap px-6 py-4 font-medium"><?= esc($item['nama'] ?? '-') ?></td>
                            <td class="px-6 py-4 font-bold"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="px-6 py-4 font-bold text-primary"><?= esc($item['nama_alat'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><?= esc($item['jenis_alat'] ?? '-') ?></td>
                            <td class="max-w-xs px-6 py-4 text-center italic">"<?= esc($item['kerusakan'] ?? '-') ?>"</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold <?= esc($statusClasses[$status] ?? 'bg-slate-100 text-slate-600') ?>"><?= esc($status ?: '-') ?></span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold uppercase <?= esc($priorityClass) ?>"><?= esc($priority ?: 'Belum Diatur') ?></span></td>
                        <?php elseif ($type === 'gedung'): ?>
                            <td class="whitespace-nowrap px-6 py-4 font-medium"><?= esc($item['nama'] ?? '-') ?></td>
                            <td class="px-6 py-4 font-bold"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="px-6 py-4"><span class="block font-semibold"><?= esc($item['gedung'] ?? '-') ?></span><span class="text-xs text-slate-500"><?= esc($item['lokasi'] ?? '-') ?></span></td>
                            <td class="max-w-xs px-6 py-4 italic"><?= esc($item['deskripsi'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <?php if (! empty($item['foto'])): ?><a class="text-primary hover:text-blue-700" href="<?= site_url('media/' . $item['foto']) ?>" target="_blank" rel="noopener" aria-label="Lihat lampiran"><span class="material-symbols-outlined">image</span></a><?php else: ?><span class="text-xs italic text-slate-400">N/A</span><?php endif ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold <?= esc($statusClasses[$status] ?? 'bg-slate-100 text-slate-600') ?>"><?= esc($status ?: '-') ?></span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold uppercase <?= esc($priorityClass) ?>"><?= esc($priority ?: 'Belum Diatur') ?></span></td>
                        <?php elseif ($type === 'kendaraan'): ?>
                            <td class="whitespace-nowrap px-6 py-4 font-medium"><?= esc($item['nama'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4 font-bold"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="px-6 py-4"><?= esc($item['jenis_kendaraan'] ?? '-') ?></td>
                            <td class="px-6 py-4"><?= esc($item['tujuan'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="block"><?= esc($formatDate($item['tgl_pinjam'] ?? null, 'd/m/Y')) ?></span><span class="text-xs text-slate-400">s/d</span><span class="block"><?= esc($formatDate($item['tgl_kembali'] ?? null, 'd/m/Y')) ?></span></td>
                        <?php elseif ($type === 'ruangan'): ?>
                            <td class="whitespace-nowrap px-6 py-4 font-medium"><?= esc($item['nama'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4 font-bold"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="px-6 py-4"><?= esc($item['ruangan'] ?? '-') ?></td>
                            <td class="px-6 py-4"><?= esc($item['acara'] ?? '-') ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><?= esc($formatDate($item['tanggal'] ?? null)) ?></td>
                            <td class="whitespace-nowrap px-6 py-4"><?= esc(($item['waktu_mulai'] ?? '-') . ' - ' . ($item['waktu_selesai'] ?? '-')) ?></td>
                        <?php elseif ($type === 'atk'): ?>
                            <td class="px-6 py-4">
                                <span class="block text-sm font-medium text-slate-700"><?= esc($item['nama'] ?? '-') ?></span>
                                <span class="block text-xs text-slate-500"><?= esc($item['nip'] ?? '') ?></span>
                                <span class="block text-[10px] text-slate-400"><?= esc($formatDate($item['created_at'] ?? null, 'd M Y, H:i')) ?></span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-bold"><?= esc($item['bidang'] ?? '-') ?></td>
                            <td class="min-w-64 px-6 py-4">
                                <ul class="space-y-2">
                                    <?php foreach ($requested as $requestedItem): ?>
                                        <li class="border-b border-slate-100 pb-2 last:border-0">
                                            <span class="font-medium text-slate-700"><?= esc($requestedItem['nama_barang'] ?? $requestedItem['nama'] ?? '-') ?></span>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="space-y-4"><?php foreach ($requested as $requestedItem): ?><div class="flex h-8 flex-col justify-center"><span class="font-bold text-slate-700"><?= esc($requestedItem['jumlah'] ?? '-') ?></span><span class="text-[9px] uppercase leading-none text-slate-400"><?= esc($requestedItem['satuan'] ?? '') ?></span></div><?php endforeach ?></div>
                            </td>
                            <td class="bg-blue-50/30 px-6 py-4 text-center">
                                <div class="space-y-4">
                                    <?php foreach ($requested as $index => $requestedItem): ?>
                                        <div class="flex h-8 items-center justify-center gap-2">
                                            <?php if ($status === 'Menunggu'): ?>
                                                <input class="w-16 rounded border border-blue-300 px-2 py-1 text-center text-xs font-bold text-blue-700 focus:ring-2 focus:ring-blue-500" type="number" min="0" name="items[<?= esc($index) ?>][diberikan]" value="<?= esc($requestedItem['diberikan'] ?? $requestedItem['jumlah'] ?? 0) ?>" form="atk-update-<?= esc($item['id']) ?>" required>
                                            <?php else: ?>
                                                <span class="text-base font-bold text-emerald-600"><?= esc($requestedItem['diberikan'] ?? 0) ?></span>
                                            <?php endif ?>
                                            <span class="text-[9px] uppercase text-slate-400"><?= esc($requestedItem['satuan'] ?? '') ?></span>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if (! empty($item['foto'])): ?><a class="text-primary hover:text-blue-700" href="<?= site_url('media/' . $item['foto']) ?>" target="_blank" rel="noopener" aria-label="Lihat nota"><span class="material-symbols-outlined">image</span></a><?php else: ?><span class="text-slate-300">—</span><?php endif ?>
                            </td>
                        <?php else: ?>
                        <?php endif ?>
                        <?php if (in_array($type, ['kendaraan', 'ruangan', 'atk'], true)): ?>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold <?= esc($statusClasses[$status] ?? 'bg-slate-100 text-slate-600') ?>"><?= esc($status ?: '-') ?></span></td>
                        <?php endif ?>
                        <?php if ($type === 'bmd'): ?>
                            <td class="whitespace-nowrap px-6 py-4 text-center"><?= esc($formatDate($item['created_at'] ?? null)) ?></td>
                        <?php endif ?>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <?php if ($type === 'atk' && $status === 'Menunggu'): ?>
                                    <form id="atk-update-<?= esc($item['id']) ?>" action="<?= site_url($updateBase . '/' . $item['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button class="rounded-md p-1.5 text-emerald-600 transition-colors hover:bg-emerald-50" type="submit" name="status" value="Disetujui" aria-label="Setujui dan kurangi stok"><span class="material-symbols-outlined text-xl">check</span></button>
                                        <button class="rounded-md p-1.5 text-red-500 transition-colors hover:bg-red-50" type="submit" name="status" value="Ditolak" aria-label="Tolak permohonan"><span class="material-symbols-outlined text-xl">close</span></button>
                                    </form>
                                <?php endif ?>
                                <a class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-blue-50 hover:text-primary" href="<?= site_url($detailBase . '/detail/' . $item['id']) ?>" aria-label="Lihat detail"><span class="material-symbols-outlined text-xl">visibility</span></a>
                                <?php if (in_array($type, ['bmd', 'peralatan', 'gedung'], true)): ?>
                                    <a class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" href="<?= site_url($type === 'bmd' ? 'bmd/export-item/' . $item['id'] : 'laporan/' . $type . '/export-item/' . $item['id']) ?>" aria-label="Unduh PDF"><span class="material-symbols-outlined text-xl">picture_as_pdf</span></a>
                                <?php endif ?>
                                <?php if ($status === 'Menunggu' && $type !== 'atk'): ?>
                                    <?php foreach (['Disetujui' => ['check', 'text-emerald-600 hover:bg-emerald-50'], 'Ditolak' => ['close', 'text-red-500 hover:bg-red-50']] as $nextStatus => [$icon, $class]): ?>
                                        <form action="<?= site_url($updateBase . '/' . $item['id']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input name="status" type="hidden" value="<?= esc($nextStatus) ?>">
                                            <button class="rounded-md p-1.5 transition-colors <?= esc($class) ?>" type="submit" aria-label="<?= esc($nextStatus) ?>"><span class="material-symbols-outlined text-xl"><?= esc($icon) ?></span></button>
                                        </form>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr id="recordEmpty" <?= $items === [] ? '' : 'class="hidden"' ?>><td class="px-6 py-12 text-center text-sm italic text-slate-400" colspan="<?= count($headers) ?>">Tidak ada data yang sesuai.</td></tr>
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('recordSearch');
    const status = document.getElementById('recordStatus');
    const bidang = document.getElementById('recordBidang');
    const rows = [...document.querySelectorAll('.record-row')];
    const empty = document.getElementById('recordEmpty');
    const filterRows = () => {
        const query = search.value.trim().toLocaleLowerCase();
        let visible = 0;
        rows.forEach((row) => {
            const matches = row.dataset.search.includes(query)
                && (!status.value || row.dataset.status === status.value)
                && (!bidang.value || row.dataset.bidang === bidang.value);
            row.classList.toggle('hidden', !matches);
            if (matches) visible += 1;
        });
        empty.classList.toggle('hidden', visible !== 0);
    };
    [search, status, bidang].forEach((input) => input.addEventListener(input === search ? 'input' : 'change', filterRows));
});
</script>
<?= $this->include('partials/footer') ?>
