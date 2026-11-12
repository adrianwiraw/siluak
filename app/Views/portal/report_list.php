<?= $this->include('partials/header', ['title' => $title]) ?>
<?php
$pageDetails = [
    'bmd' => [
        'title' => 'Daftar Kebutuhan BMD',
        'subtitle' => 'Daftar usulan pengadaan Barang Milik Daerah (BMD).',
        'headers' => ['Nama', 'Bidang', 'Kode', 'Program', 'Kegiatan', 'Output', 'Barang', 'Ket', 'Status'],
    ],
    'alat' => [
        'title' => 'Daftar Laporan Kerusakan Alat Kantor',
        'subtitle' => 'Monitoring dan pelaporan kerusakan fasilitas kantor.',
        'headers' => ['Nama Pelapor', 'Bidang', 'Jenis Alat', 'Nama Barang', 'Deskripsi', 'Status'],
    ],
    'gedung' => [
        'title' => 'Daftar Laporan Kerusakan Gedung dan Bangunan',
        'subtitle' => 'Monitoring perbaikan gedung dan bangunan instansi.',
        'headers' => ['Nama Pelapor', 'Bidang', 'No. Telepon', 'Gedung', 'Lokasi', 'Deskripsi', 'Foto', 'Status'],
    ],
    'kendaraan' => [
        'title' => 'Daftar Peminjaman Kendaraan Dinas',
        'subtitle' => 'Monitoring dan pengajuan izin penggunaan kendaraan operasional.',
        'headers' => ['Nama Peminjam', 'Bidang', 'Kendaraan', 'Tujuan', 'Waktu Pinjam', 'Dokumen', 'Status'],
    ],
    'ruangan' => [
        'title' => 'Daftar Peminjaman Ruangan Rapat',
        'subtitle' => 'Reservasi penggunaan ruang pertemuan dan aula kantor.',
        'headers' => ['Nama Peminjam', 'Bidang', 'Ruangan', 'Acara', 'Tanggal', 'Waktu', 'Status'],
    ],
    'atk' => [
        'title' => 'Daftar Permohonan ATK',
        'subtitle' => 'Daftar pengajuan alat tulis kantor untuk kebutuhan operasional.',
        'headers' => ['Nama Pemohon', 'Bidang', 'Detail Barang', 'Status'],
    ],
];
$page = $pageDetails[$type] ?? ['title' => $title, 'subtitle' => '', 'headers' => []];
$statusClasses = [
    'Menunggu' => 'border-orange-100 bg-orange-50 text-orange-600',
    'Disetujui' => 'border-blue-100 bg-blue-50 text-blue-600',
    'Ditolak' => 'border-red-100 bg-red-50 text-red-600',
    'Selesai' => 'border-emerald-100 bg-emerald-50 text-emerald-600',
];
$formatCreatedAt = static function (array $item): string {
    $timestamp = strtotime((string) ($item['created_at'] ?? ''));
    return $timestamp === false ? '-' : date('d M Y, H:i', $timestamp);
};
$itemsFor = static function (array $item): array {
    $decoded = json_decode((string) ($item['items'] ?? '[]'), true);
    return is_array($decoded) ? $decoded : [];
};
?>
<div class="flex-grow bg-gray-50">
    <div class="px-8 pt-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800"><?= esc($page['title']) ?></h1>
                <p class="mt-1 text-sm text-slate-500"><?= esc($page['subtitle']) ?></p>
            </div>
        </div>
    </div>

    <div class="px-8 pb-8">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <?php foreach ($page['headers'] as $header): ?>
                                <th class="whitespace-nowrap px-6 py-4 text-xs font-bold uppercase text-slate-500"><?= esc($header) ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if ($items === []): ?>
                            <?php
                            $emptyMessages = [
                                'bmd' => 'Belum ada usulan yang masuk.',
                                'alat' => 'Belum ada laporan kerusakan yang masuk.',
                                'gedung' => 'Belum ada laporan kerusakan gedung yang masuk.',
                                'kendaraan' => 'Belum ada permohonan peminjaman kendaraan yang masuk.',
                                'ruangan' => 'Belum ada jadwal peminjaman ruangan.',
                                'atk' => 'Belum ada data permohonan ATK yang diajukan.',
                            ];
                            ?>
                            <tr>
                                <td class="px-6 py-12 text-center text-sm italic text-slate-400" colspan="<?= count($page['headers']) ?>">
                                    <?= esc($emptyMessages[$type] ?? 'Belum ada data.') ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <?php
                                $status = (string) ($item['status'] ?? '');
                                $statusClass = $statusClasses[$status] ?? 'border-slate-100 bg-slate-50 text-slate-600';
                                $requestedItems = $itemsFor($item);
                                ?>
                                <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50">
                                    <?php if ($type === 'bmd'): ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <?php foreach (['bidang', 'kode', 'program', 'kegiatan', 'output'] as $field): ?>
                                            <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item[$field] ?? '-') ?></td>
                                        <?php endforeach ?>
                                        <td class="px-6 py-4">
                                            <ul class="list-inside list-disc space-y-1 text-sm">
                                                <?php foreach ($requestedItems as $requestedItem): ?>
                                                    <li>
                                                        <span class="font-medium text-slate-700"><?= esc($requestedItem['nama_barang'] ?? '-') ?></span>
                                                        <span class="text-xs text-slate-500">(<?= esc($requestedItem['jumlah'] ?? '-') ?> <?= esc($requestedItem['satuan'] ?? '') ?>)</span>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item['keterangan'] ?? '-') ?></td>
                                    <?php elseif ($type === 'alat'): ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <?php foreach (['bidang', 'jenis_alat', 'nama_alat', 'kerusakan'] as $field): ?>
                                            <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item[$field] ?? '-') ?></td>
                                        <?php endforeach ?>
                                    <?php elseif ($type === 'gedung'): ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <?php foreach (['bidang', 'telepon', 'gedung', 'lokasi', 'deskripsi'] as $field): ?>
                                            <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item[$field] ?? '-') ?></td>
                                        <?php endforeach ?>
                                        <td class="px-6 py-4 text-center">
                                            <?php if (! empty($item['foto'])): ?>
                                                <a class="inline-flex size-8 items-center justify-center rounded-md border border-blue-200 bg-blue-50 text-blue-600 transition hover:bg-blue-100" href="<?= site_url('media/' . $item['foto']) ?>" target="_blank" rel="noopener" title="Lihat Foto"><i class="fa-regular fa-image"></i></a>
                                            <?php else: ?>
                                                <span class="text-xs italic text-slate-300">N/A</span>
                                            <?php endif ?>
                                        </td>
                                    <?php elseif ($type === 'kendaraan'): ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <?php foreach (['bidang', 'jenis_kendaraan', 'tujuan'] as $field): ?>
                                            <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item[$field] ?? '-') ?></td>
                                        <?php endforeach ?>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-700"><?= esc(! empty($item['tgl_pinjam']) ? date('d/m/Y', strtotime($item['tgl_pinjam'])) : '-') ?></div>
                                            <div class="my-0.5 text-[10px] tracking-wider text-slate-400">s/d</div>
                                            <div class="text-sm font-medium text-slate-700"><?= esc(! empty($item['tgl_kembali']) ? date('d/m/Y', strtotime($item['tgl_kembali'])) : '-') ?></div>
                                        </td>
                                        <td class="px-2 py-4 text-center">
                                            <div class="mx-auto flex w-fit flex-col gap-2">
                                                <a class="inline-flex w-full items-center justify-center gap-1.5 rounded border border-slate-200 bg-white px-1.5 py-1 text-[10px] font-bold text-slate-600 shadow-sm transition hover:border-blue-200 hover:text-blue-600" href="<?= site_url('umpeg/laporan/pinjam_kendaraan/download/' . $item['id']) ?>"><i class="fa-solid fa-download"></i> UNDUH SURAT</a>
                                                <?php if (! empty($item['surat_permohonan'])): ?>
                                                    <a class="inline-flex w-full items-center justify-center gap-1.5 rounded border border-blue-100 bg-blue-50 px-1.5 py-1 text-[10px] font-bold text-blue-600 transition hover:bg-blue-100" href="<?= site_url('media/' . $item['surat_permohonan']) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-eye"></i> LIHAT SCAN</a>
                                                <?php else: ?>
                                                    <form action="<?= site_url('umpeg/laporan/pinjam_kendaraan/upload/' . $item['id']) ?>" method="post" enctype="multipart/form-data" class="w-full max-w-[140px]">
                                                        <?= csrf_field() ?>
                                                        <div class="flex flex-col gap-1">
                                                            <input class="block w-full text-[9px] text-slate-500 file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-[9px] file:font-semibold file:text-slate-700 hover:file:bg-slate-200" type="file" name="surat" required>
                                                            <button class="w-full rounded bg-slate-800 py-1 text-[9px] font-bold text-white transition hover:bg-slate-700" type="submit">UPLOAD SCAN</button>
                                                        </div>
                                                    </form>
                                                <?php endif ?>
                                            </div>
                                        </td>
                                    <?php elseif ($type === 'ruangan'): ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <?php foreach (['bidang', 'ruangan', 'acara'] as $field): ?>
                                            <td class="px-6 py-4 text-sm text-slate-700"><?= esc($item[$field] ?? '-') ?></td>
                                        <?php endforeach ?>
                                        <td class="px-6 py-4 text-sm text-slate-700"><?= esc(! empty($item['tanggal']) ? date('d M Y', strtotime($item['tanggal'])) : '-') ?></td>
                                        <td class="px-6 py-4 text-sm text-slate-700"><?= esc(($item['waktu_mulai'] ?? '-') . ' - ' . ($item['waktu_selesai'] ?? '-')) ?></td>
                                    <?php else: ?>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-600"><?= esc($item['nama'] ?? '-') ?></span>
                                                <span class="mt-1 text-xs font-normal text-slate-400"><?= esc($formatCreatedAt($item)) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-slate-600"><?= esc($item['bidang'] ?? '-') ?></td>
                                        <td class="px-6 py-4">
                                            <ul class="space-y-2">
                                                <?php foreach ($requestedItems as $requestedItem): ?>
                                                    <li class="border-b border-slate-50 pb-2 text-sm last:border-0">
                                                        <div class="flex items-center gap-2 font-medium text-slate-700"><i class="fas fa-caret-right text-xs text-slate-300"></i><?= esc($requestedItem['nama_barang'] ?? $requestedItem['nama'] ?? '-') ?></div>
                                                        <div class="mt-0.5 flex gap-3 pl-4 text-xs">
                                                            <span class="text-slate-500">Jumlah: <span class="font-bold text-slate-700"><?= esc($requestedItem['jumlah'] ?? '-') ?> <?= esc($requestedItem['satuan'] ?? '') ?></span></span>
                                                            <?php if (in_array($status, ['Selesai', 'Disetujui'], true)): ?>
                                                                <span class="rounded border border-blue-100 bg-blue-50 px-1.5 text-blue-600">Diberi: <b><?= esc($requestedItem['diberikan'] ?? 0) ?> <?= esc($requestedItem['satuan'] ?? '') ?></b></span>
                                                            <?php elseif ($status === 'Ditolak'): ?>
                                                                <span class="italic text-red-500">Ditolak</span>
                                                            <?php else: ?>
                                                                <span class="italic text-slate-400">(Proses)</span>
                                                            <?php endif ?>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </td>
                                    <?php endif ?>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase <?= esc($statusClass) ?>"><?= esc($status !== '' ? $status : '-') ?></span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->include('partials/footer') ?>
