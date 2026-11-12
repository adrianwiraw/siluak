<?= $this->include('partials/header', ['title' => 'Dashboard Admin']) ?>
<?php
$maxData = max(array_map('intval', $chartData ?: [0]));
$maxData = max(1, $maxData);
$xCoords = [20, 146, 272, 398, 524, 650, 776];
$points = [];
foreach (array_values($chartData) as $index => $value) {
    $points[] = $xCoords[$index] . ',' . (180 - ((int) $value / $maxData) * 160);
}
$weeklyPoints = implode(' ', $points);
$pendingPercentage = $total > 0 ? (int) round(($pending / $total) * 100) : 0;
$weeklyTrendIcon = $weeklyChange >= 0 ? 'trending_up' : 'trending_down';
$weeklyTrendClass = $weeklyChange >= 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600';
?>
<div class="mb-8 rounded-r-xl border-l-4 border-primary bg-gradient-to-r from-primary/10 to-transparent p-6">
    <h3 class="mb-2 text-xl font-bold text-slate-900">Selamat Datang, Admin! 👋</h3>
    <p class="max-w-3xl text-sm leading-relaxed text-slate-600">Di panel ini, Anda dapat memantau statistik Laporan dan mengelola layanan dengan mudah. Anda juga dapat melihat aktivitas terbaru serta laporan yang memerlukan tindakan segera. Anda juga dapat mengakses menu navigasi di sebelah kiri untuk mengakses berbagai menu yang tersedia.</p>
</div>

<div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Ringkasan Layanan</h2>
        <p class="mt-1 text-slate-500">Pantau kinerja sistem dan layanan terkini.</p>
    </div>
    <a class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/20 transition-colors hover:bg-blue-600" href="<?= site_url('admin/layanan/add') ?>">
        <span class="material-symbols-outlined">add</span>Layanan Baru
    </a>
</div>

<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <section class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="rounded-lg bg-blue-100 p-2 text-primary"><span class="material-symbols-outlined">assignment_add</span></div>
            <span class="flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium <?= esc($weeklyTrendClass) ?>">
                <span class="material-symbols-outlined text-sm"><?= esc($weeklyTrendIcon) ?></span><?= esc(abs($weeklyChange)) ?>%
            </span>
        </div>
        <div><p class="text-sm font-medium text-slate-500">Permohonan Baru</p><p class="mt-1 text-3xl font-bold text-slate-900"><?= esc($weekCurrent) ?></p></div>
    </section>
    <section class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="rounded-lg bg-orange-100 p-2 text-orange-500"><span class="material-symbols-outlined">priority_high</span></div>
            <span class="rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-500"><?= esc($pendingPercentage) ?>%</span>
        </div>
        <div><p class="text-sm font-medium text-slate-500">Perlu Tindakan</p><p class="mt-1 text-3xl font-bold text-slate-900"><?= esc($pending) ?></p></div>
    </section>
    <section class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between"><div class="rounded-lg bg-emerald-100 p-2 text-emerald-500"><span class="material-symbols-outlined">check_circle</span></div></div>
        <div><p class="text-sm font-medium text-slate-500">Layanan Selesai</p><p class="mt-1 text-3xl font-bold text-slate-900"><?= esc($completed) ?></p></div>
    </section>
</div>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
        <h3 class="mb-6 text-lg font-bold text-slate-900">Statistik Permohonan (Minggu Ini)</h3>
        <div class="relative h-64 w-full px-2">
            <svg class="h-full w-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 800 200" role="img" aria-label="Grafik permohonan minggu ini">
                <line x1="0" y1="180" x2="800" y2="180" stroke="#e2e8f0" stroke-width="1"></line>
                <line x1="0" y1="100" x2="800" y2="100" stroke="#f1f5f9" stroke-width="1"></line>
                <line x1="0" y1="20" x2="800" y2="20" stroke="#f1f5f9" stroke-width="1"></line>
                <polyline points="<?= esc($weeklyPoints) ?>" fill="none" stroke="#135bec" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                <?php foreach (array_values($chartData) as $index => $value): ?>
                    <?php $y = 180 - (((int) $value / $maxData) * 160); ?>
                    <circle class="chart-dot cursor-pointer fill-white stroke-primary" cx="<?= esc($xCoords[$index]) ?>" cy="<?= esc($y) ?>" r="5" stroke-width="2" data-value="<?= esc($value) ?> Laporan"></circle>
                <?php endforeach ?>
            </svg>
        </div>
        <div class="mt-4 flex justify-between text-xs font-semibold text-slate-400">
            <?php foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day): ?><span class="w-20 text-center"><?= esc($day) ?></span><?php endforeach ?>
        </div>
    </section>
    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-6 text-lg font-bold text-slate-900">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <?php foreach ($activities as $activity): ?>
                <?php
                $activityTime = $activity['created_at'] !== '' ? strtotime($activity['created_at']) : false;
                $elapsed = $activityTime === false ? null : max(0, time() - $activityTime);
                $relativeTime = match (true) {
                    $elapsed === null => '-',
                    $elapsed < 60 => 'Baru saja',
                    $elapsed < 3600 => floor($elapsed / 60) . ' menit yang lalu',
                    $elapsed < 86400 => floor($elapsed / 3600) . ' jam yang lalu',
                    default => floor($elapsed / 86400) . ' hari yang lalu',
                };
                ?>
                <div class="flex gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-<?= esc($activity['color']) ?>-100 text-<?= esc($activity['color']) ?>-500">
                        <span class="material-symbols-outlined text-base"><?= esc($activity['icon']) ?></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-800"><strong><?= esc($activity['name']) ?></strong> mengajukan <?= esc($activity['type']) ?>.</p>
                        <p class="text-xs text-slate-500"><?= esc($relativeTime) ?></p>
                    </div>
                </div>
            <?php endforeach ?>
            <?php if ($activities === []): ?>
                <p class="text-sm italic text-slate-500">Belum ada aktivitas.</p>
            <?php endif ?>
        </div>
    </section>
</div>

<section class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-6"><h3 class="text-lg font-bold text-slate-900">Daftar Perlu Tindakan</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr><th class="px-6 py-4">Pemohon</th><th class="px-6 py-4">Layanan</th><th class="px-6 py-4">Tanggal</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($pendingRequests as $request): ?>
                    <?php
                    $detailPath = match ($request['type']) {
                        'peralatan', 'gedung' => 'laporan/' . $request['type'] . '/detail/' . $request['id'],
                        'kendaraan' => 'peminjaman/kendaraan/detail/' . $request['id'],
                        'ruangan' => 'peminjaman/ruang/detail/' . $request['id'],
                        default => $request['type'] . '/detail/' . $request['id'],
                    };
                    ?>
                    <tr>
                        <td class="px-6 py-4 font-medium text-slate-900"><?= esc($request['name']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= esc($request['title']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= esc($request['created_at'] !== '' ? date('d/m/Y', strtotime($request['created_at'])) : '-') ?></td>
                        <td class="px-6 py-4"><span class="rounded-full bg-orange-100 px-2 py-1 text-xs text-orange-600"><?= esc($request['status']) ?></span></td>
                        <td class="px-6 py-4"><a class="mr-3 text-primary hover:text-blue-700" href="<?= site_url($detailPath) ?>">Detail</a></td>
                    </tr>
                <?php endforeach ?>
                <?php if ($pendingRequests === []): ?>
                    <tr><td class="px-6 py-8 text-center text-slate-500" colspan="5">Semua tugas sudah selesai dikerjakan!</td></tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tooltip = document.createElement('div');
    tooltip.className = 'chart-tooltip';
    document.body.appendChild(tooltip);
    document.querySelectorAll('.chart-dot').forEach((dot) => {
        dot.addEventListener('mouseenter', () => {
            tooltip.textContent = dot.dataset.value;
            tooltip.style.opacity = '1';
        });
        dot.addEventListener('mousemove', (event) => {
            tooltip.style.left = `${event.clientX + 10}px`;
            tooltip.style.top = `${event.clientY + 10}px`;
        });
        dot.addEventListener('mouseleave', () => { tooltip.style.opacity = '0'; });
    });
});
</script>
<?= $this->include('partials/footer') ?>
