<?= $this->include('partials/header', ['title' => 'Status Tiket Permohonan']) ?>
<?php
$statusStyles = [
    'Menunggu' => ['border-amber-200 bg-amber-50 text-amber-800', 'hourglass_top', 'Permohonan masuk dan sedang menunggu tindak lanjut.'],
    'Disetujui' => ['border-blue-200 bg-blue-50 text-blue-800', 'check_circle', 'Permohonan telah disetujui.'],
    'Proses' => ['border-indigo-200 bg-indigo-50 text-indigo-800', 'autorenew', 'Permohonan sedang diproses.'],
    'Selesai' => ['border-emerald-200 bg-emerald-50 text-emerald-800', 'task_alt', 'Permohonan telah selesai ditangani.'],
    'Ditolak' => ['border-red-200 bg-red-50 text-red-800', 'cancel', 'Permohonan belum dapat disetujui. Silakan hubungi petugas untuk informasi lebih lanjut.'],
];
[$statusClass, $statusIcon, $statusMessage] = $statusStyles[$status] ?? ['border-slate-200 bg-slate-50 text-slate-700', 'info', 'Status permohonan diperbarui.'];
$createdTimestamp = strtotime($createdAt);
?>
<section class="mx-auto max-w-3xl px-6 py-14 md:px-8">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-dinas-blue px-8 py-8 text-white md:px-10">
            <span class="material-symbols-outlined text-4xl">confirmation_number</span>
            <h1 class="mt-3 text-2xl font-extrabold">Status tiket permohonan</h1>
            <p class="mt-2 text-blue-100">Simpan kode ini untuk mengecek perkembangan permohonan kapan saja.</p>
        </div>
        <div class="space-y-6 p-8 md:p-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Kode tiket</p>
                <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                    <code id="ticket-code-value" class="min-w-0 flex-1 break-all rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-3 font-mono text-lg font-bold tracking-wide text-slate-900"><?= esc($code) ?></code>
                    <button class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-slate-300 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50" type="button" onclick="navigator.clipboard.writeText(document.getElementById('ticket-code-value').textContent.trim()).then(() => { this.dataset.copied = 'true'; this.querySelector('span:last-child').textContent = 'Tersalin'; }).catch(() => { window.prompt('Salin kode tiket ini:', document.getElementById('ticket-code-value').textContent.trim()); })">
                        <span class="material-symbols-outlined text-lg">content_copy</span><span>Salin kode</span>
                    </button>
                </div>
            </div>

            <dl class="grid gap-4 border-y border-slate-100 py-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Jenis layanan</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= esc($title) ?></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal pengajuan</dt>
                    <dd class="mt-1 font-semibold text-slate-800"><?= $createdTimestamp === false ? '-' : esc(date('d M Y, H:i', $createdTimestamp)) ?></dd>
                </div>
            </dl>

            <div class="rounded-xl border p-4 <?= esc($statusClass) ?>">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined"><?= esc($statusIcon) ?></span>
                    <div><p class="font-bold">Status: <?= esc($status) ?></p><p class="mt-1 text-sm"><?= esc($statusMessage) ?></p></div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a class="inline-flex items-center justify-center gap-2 rounded-lg bg-dinas-blue px-5 py-3 font-bold text-white transition hover:bg-blue-800" href="<?= site_url('tiket') ?>">
                    <span class="material-symbols-outlined">search</span>Cek tiket lain
                </a>
                <a class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 px-5 py-3 font-bold text-slate-700 transition hover:bg-slate-50" href="<?= site_url('/') ?>">
                    <span class="material-symbols-outlined">home</span>Kembali ke beranda
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->include('partials/footer') ?>
