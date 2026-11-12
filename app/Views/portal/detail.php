<?= $this->include('partials/header', ['title' => 'Detail Permohonan']) ?>
<?php
$serviceTitles = [
    'bmd' => 'Usulan Rencana Kebutuhan BMD',
    'alat' => 'Pelaporan Kerusakan Peralatan Kantor',
    'gedung' => 'Pelaporan Kerusakan Gedung dan Bangunan',
    'kendaraan' => 'Peminjaman Kendaraan Dinas',
    'ruangan' => 'Peminjaman Ruangan Rapat',
    'atk' => 'Permohonan ATK',
];
$requestedItems = json_decode((string) ($record['items'] ?? '[]'), true);
$requestedItems = is_array($requestedItems) ? $requestedItems : [];
?>
<div class="mx-auto max-w-6xl px-6 py-10 md:px-12">
    <div class="mb-6">
        <a class="inline-flex items-center gap-2 text-sm font-semibold text-dinas-blue transition hover:text-blue-800" href="<?= site_url('/') ?>">
            <span class="material-symbols-outlined text-lg">arrow_back</span>Kembali ke portal
        </a>
        <h1 class="mt-4 text-3xl font-bold text-slate-800">Detail <?= esc($serviceTitles[$type] ?? 'Permohonan') ?></h1>
        <p class="mt-1 text-slate-500">Informasi permohonan #<?= esc($record['id'] ?? '') ?></p>
    </div>
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="font-bold text-slate-800">Informasi Pemohon</h2>
        </div>
        <dl class="divide-y divide-slate-100 px-6">
            <?php foreach ($record as $field => $value): ?>
                <?php if ($field === 'items'): ?>
                    <div class="grid grid-cols-1 gap-2 py-4 md:grid-cols-[minmax(150px,.35fr)_minmax(0,1fr)]">
                        <dt class="text-sm font-semibold text-slate-500">Detail Barang</dt>
                        <dd>
                            <?php if ($requestedItems !== []): ?>
                                <ul class="space-y-2">
                                    <?php foreach ($requestedItems as $requestedItem): ?>
                                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm text-slate-700">
                                            <span class="font-semibold"><?= esc($requestedItem['nama_barang'] ?? $requestedItem['nama'] ?? 'Barang') ?></span>
                                            <span class="text-slate-500"> · <?= esc($requestedItem['jumlah'] ?? '-') ?> <?= esc($requestedItem['satuan'] ?? '') ?></span>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            <?php else: ?>- <?php endif ?>
                        </dd>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-2 py-4 md:grid-cols-[minmax(150px,.35fr)_minmax(0,1fr)]">
                        <dt class="text-sm font-semibold text-slate-500"><?= esc(ucwords(str_replace('_', ' ', $field))) ?></dt>
                        <dd class="break-words text-sm text-slate-800">
                            <?php if (in_array($field, ['foto', 'surat_permohonan'], true) && is_string($value) && $value !== ''): ?>
                                <a class="text-dinas-blue hover:underline" href="<?= site_url('media/' . $value) ?>" target="_blank" rel="noopener">Lihat / unduh file</a>
                                <?php if ($field === 'foto'): ?><img class="mt-3 max-h-80 max-w-full rounded-lg border border-slate-200 object-contain" src="<?= site_url('media/' . $value) ?>" alt="Lampiran permohonan"><?php endif ?>
                            <?php else: ?>
                                <?= esc(is_scalar($value) || $value === null ? (string) $value : json_encode($value)) ?>
                            <?php endif ?>
                        </dd>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </dl>
    </section>
</div>
<?= $this->include('partials/footer') ?>
