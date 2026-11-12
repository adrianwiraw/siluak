<?= $this->include('partials/header', ['title' => 'Dashboard Admin']) ?>
<h1>Dashboard Admin</h1>
<div class="grid">
    <section class="card"><h2><?= esc($total) ?></h2><p>Total permohonan</p></section>
    <section class="card"><h2><?= esc($pending) ?></h2><p>Menunggu tindakan</p></section>
    <section class="card"><h2><?= esc($completed) ?></h2><p>Selesai</p></section>
    <section class="card"><h2><?= esc($weeklyChange) ?>%</h2><p>Perubahan mingguan (<?= esc($weekCurrent) ?> vs <?= esc($weekLast) ?>)</p></section>
</div>
<section class="card">
    <h2>Permohonan minggu ini</h2>
    <div class="row"><?php foreach ($chartData as $day => $count): ?><span class="card"><?= esc(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'][$day]) ?>: <?= esc($count) ?></span><?php endforeach ?></div>
</section>
<section class="card">
    <h2>Menu administrasi</h2>
    <div class="grid">
        <?php foreach ($records as $type => $record): ?><p><a class="button" href="<?= site_url($type === 'peralatan' || $type === 'gedung' ? 'laporan/' . $type : ($type === 'kendaraan' || $type === 'ruangan' ? 'peminjaman/' . ($type === 'ruangan' ? 'ruang' : $type) : $type)) ?>"><?= esc($record['title']) ?></a></p><?php endforeach ?>
        <p><a class="button" href="<?= site_url('manajemen/stok') ?>">Manajemen Stok</a></p>
        <p><a class="button" href="<?= site_url('admin/layanan/index') ?>">Menu Layanan</a></p>
    </div>
</section>
<section class="card">
    <form method="get" action="<?= site_url('search') ?>"><label for="q">Cari permohonan</label><div class="row"><input id="q" name="q" value="<?= esc($query ?? '') ?>"><button type="submit">Cari</button></div></form>
</section>
<?= $this->include('partials/footer') ?>
