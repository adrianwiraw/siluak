<?= $this->include('partials/header', ['title' => 'Hasil Pencarian']) ?>
<?php
$groups = [];
foreach ($results as $result) {
    $groups[$result['title']][] = $result;
}
$detailPaths = [
    'bmd' => 'bmd/detail/',
    'peralatan' => 'laporan/peralatan/detail/',
    'gedung' => 'laporan/gedung/detail/',
    'kendaraan' => 'peminjaman/kendaraan/detail/',
    'ruangan' => 'peminjaman/ruang/detail/',
    'atk' => 'atk/detail/',
];
$subfields = [
    'bmd' => 'program',
    'peralatan' => 'nama_alat',
    'gedung' => 'gedung',
    'kendaraan' => 'jenis_kendaraan',
    'ruangan' => 'ruangan',
    'atk' => 'bidang',
];
$statusClasses = [
    'Selesai' => 'bg-emerald-50 text-emerald-600',
    'Disetujui' => 'bg-emerald-50 text-emerald-600',
    'Ditolak' => 'bg-red-50 text-red-600',
];
?>
<div class="p-3">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Hasil Pencarian</h2>
        <p class="text-slate-500">Kata kunci: "<?= esc($query) ?>"</p>
    </div>

    <?php foreach ($groups as $title => $group): ?>
        <?php $groupType = $group[0]['type']; ?>
        <section class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                <h3 class="font-bold text-slate-700"><?= esc($title) ?> (<?= count($group) ?>)</h3>
            </div>
            <div class="divide-y divide-slate-100">
                <?php foreach ($group as $result): ?>
                    <?php
                    $row = $result['row'];
                    $isClickable = isset($detailPaths[$result['type']]);
                    $subfield = $subfields[$result['type']] ?? '';
                    $status = (string) ($row['status'] ?? '-');
                    $statusClass = $statusClasses[$status] ?? 'bg-orange-50 text-orange-600';
                    $createdAt = strtotime((string) ($row['created_at'] ?? ''));
                    ?>
                    <?php if ($isClickable): ?>
                        <a class="group block cursor-pointer p-4 transition-all hover:bg-slate-50" href="<?= site_url($detailPaths[$result['type']] . $row['id']) ?>">
                    <?php else: ?>
                        <div class="flex items-center justify-between bg-white p-4">
                    <?php endif ?>
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 transition-colors <?= $isClickable ? 'group-hover:text-blue-600' : '' ?>"><?= esc($row['nama'] ?? $row['name'] ?? 'Tanpa Nama') ?></p>
                                <p class="mt-1 text-sm text-slate-500">
                                    <?= esc($row[$subfield] ?? 'Detail tidak tersedia') ?> —
                                    <span class="text-slate-400"><?= esc($createdAt === false ? '-' : date('d M Y', $createdAt)) ?></span>
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-4">
                                <span class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-tight <?= esc($statusClass) ?>"><?= esc($status) ?></span>
                                <?php if ($isClickable): ?><span class="material-symbols-outlined text-slate-300 transition-all group-hover:translate-x-1 group-hover:text-blue-500">chevron_right</span><?php endif ?>
                            </div>
                        </div>
                    <?php if ($isClickable): ?></a><?php else: ?></div><?php endif ?>
                <?php endforeach ?>
            </div>
        </section>
    <?php endforeach ?>

    <?php if ($groups === []): ?>
        <div class="rounded-xl border border-dashed border-slate-300 bg-white py-20 text-center">
            <div class="mb-2 text-slate-400"><span class="material-symbols-outlined text-6xl">search_off</span></div>
            <p class="font-medium text-slate-500">Tidak ada hasil yang ditemukan untuk "<?= esc($query) ?>"</p>
            <p class="mt-1 text-sm text-slate-400">Coba gunakan kata kunci lain atau periksa ejaan Anda.</p>
        </div>
    <?php endif ?>
</div>
<?= $this->include('partials/footer') ?>
