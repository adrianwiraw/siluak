<?= $this->include('partials/header', ['title' => $title]) ?>
<?php
$listPath = match ($type) {
    'peralatan', 'gedung' => 'laporan/' . $type,
    'kendaraan' => 'peminjaman/kendaraan',
    'ruangan' => 'peminjaman/ruang',
    default => $type,
};
$updatePath = match ($type) {
    'peralatan', 'gedung' => 'laporan/' . $type . '/update/' . $item['id'],
    'bmd' => 'bmd/update/' . $item['id'],
    default => 'admin/update-status/' . $type . '/' . $item['id'],
};
$statusClasses = [
    'Menunggu' => 'bg-orange-100 text-orange-700',
    'Disetujui' => 'bg-blue-100 text-blue-700',
    'Proses' => 'bg-indigo-100 text-indigo-700',
    'Selesai' => 'bg-emerald-100 text-emerald-700',
    'Ditolak' => 'bg-red-100 text-red-700',
];
$atkItems = json_decode((string) ($item['items'] ?? '[]'), true);
$atkItems = is_array($atkItems) ? $atkItems : [];
?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
            <a class="hover:text-primary" href="<?= site_url('dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a class="hover:text-primary" href="<?= site_url($listPath) ?>"><?= esc($title) ?></a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="font-medium text-slate-800">Detail #<?= esc($item['id']) ?></span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900"><?= esc($title) ?> #<?= esc($item['id']) ?></h2>
        <p class="mt-1 text-slate-500">Informasi lengkap permohonan dan status tindak lanjut.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50" href="<?= site_url($listPath) ?>">
            <span class="material-symbols-outlined text-lg">arrow_back</span>Kembali
        </a>
        <?php if (in_array($type, ['bmd', 'peralatan', 'gedung'], true)): ?>
            <a class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-600" href="<?= site_url($type === 'bmd' ? 'bmd/export-item/' . $item['id'] : 'laporan/' . $type . '/export-item/' . $item['id']) ?>">
                <span class="material-symbols-outlined text-lg">picture_as_pdf</span>Unduh PDF
            </a>
        <?php endif ?>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <h3 class="font-bold text-slate-800">Informasi Permohonan</h3>
            <?php $status = (string) ($item['status'] ?? '-'); ?>
            <span class="rounded-full px-3 py-1 text-xs font-bold <?= esc($statusClasses[$status] ?? 'bg-slate-100 text-slate-600') ?>"><?= esc($status) ?></span>
        </div>
        <dl class="divide-y divide-slate-100">
            <?php foreach ($item as $field => $value): ?>
                <?php if ($field === 'items'): ?>
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-[minmax(150px,.35fr)_minmax(0,1fr)]">
                        <dt class="text-sm font-semibold text-slate-500">Detail Barang</dt>
                        <dd>
                            <?php if ($atkItems !== []): ?>
                                <ul class="space-y-2">
                                    <?php foreach ($atkItems as $requestedItem): ?>
                                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm text-slate-700">
                                            <span class="font-semibold"><?= esc($requestedItem['nama_barang'] ?? $requestedItem['nama'] ?? 'Barang') ?></span>
                                            <span class="text-slate-500"> · <?= esc($requestedItem['jumlah'] ?? '-') ?> <?= esc($requestedItem['satuan'] ?? '') ?></span>
                                            <?php if (isset($requestedItem['diberikan'])): ?><span class="ml-2 text-blue-600">Diberikan: <?= esc($requestedItem['diberikan']) ?></span><?php endif ?>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            <?php else: ?>- <?php endif ?>
                        </dd>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-2 px-6 py-4 sm:grid-cols-[minmax(150px,.35fr)_minmax(0,1fr)]">
                        <dt class="text-sm font-semibold text-slate-500"><?= esc(ucwords(str_replace('_', ' ', $field))) ?></dt>
                        <dd class="break-words text-sm text-slate-800">
                            <?php if (in_array($field, ['foto', 'surat_permohonan'], true) && is_string($value) && $value !== ''): ?>
                                <a class="font-semibold text-primary hover:underline" href="<?= site_url('media/' . $value) ?>" target="_blank" rel="noopener">Lihat / unduh file</a>
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

    <section class="h-fit rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="font-bold text-slate-800">Tindak Lanjut</h3>
            <p class="mt-1 text-sm text-slate-500">Perbarui status permohonan.</p>
        </div>
        <form action="<?= site_url($updatePath) ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <label class="block text-sm font-semibold text-slate-700" for="status">Status
                <select class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-normal text-slate-700 focus:ring-primary" id="status" name="status">
                    <?php foreach (['Menunggu', 'Disetujui', 'Proses', 'Selesai', 'Ditolak'] as $option): ?><option value="<?= esc($option) ?>" <?= $status === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach ?>
                </select>
            </label>
            <?php if (in_array($type, ['peralatan', 'gedung'], true)): ?>
                <label class="block text-sm font-semibold text-slate-700" for="prioritas">Prioritas
                    <select class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-normal text-slate-700 focus:ring-primary" id="prioritas" name="prioritas">
                        <?php foreach (['Belum Diatur', 'Tinggi', 'Sedang', 'Rendah'] as $option): ?><option value="<?= esc($option) ?>" <?= ($item['prioritas'] ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option><?php endforeach ?>
                    </select>
                </label>
            <?php endif ?>
            <?php if ($type === 'atk'): ?>
                <fieldset class="space-y-3">
                    <legend class="text-sm font-semibold text-slate-700">Jumlah yang diberikan</legend>
                    <?php foreach ($atkItems as $index => $requestedItem): ?>
                        <label class="block text-xs text-slate-600"><?= esc($requestedItem['nama_barang'] ?? 'Barang') ?> (<?= esc($requestedItem['satuan'] ?? '') ?>)
                            <input class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:ring-primary" type="number" min="0" name="items[<?= esc($index) ?>][diberikan]" value="<?= esc($requestedItem['diberikan'] ?? $requestedItem['jumlah'] ?? 0) ?>">
                        </label>
                    <?php endforeach ?>
                </fieldset>
            <?php endif ?>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-600" type="submit">
                <span class="material-symbols-outlined text-lg">save</span>Simpan Perubahan
            </button>
        </form>
    </section>
</div>
<?= $this->include('partials/footer') ?>
