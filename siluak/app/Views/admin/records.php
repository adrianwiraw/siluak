<?= $this->include('partials/header', ['title' => $title]) ?>
<h1><?= esc($title) ?></h1>
<p><a class="button" href="<?= site_url('dashboard') ?>">Dashboard</a>
    <a class="button" href="<?= site_url($type === 'kendaraan' ? 'peminjaman/kendaraan/export-kendaraan' : ($type === 'ruangan' ? 'peminjaman/ruang/export-ruang' : ($type === 'peralatan' || $type === 'gedung' ? 'laporan/' . $type . '/export-excel' : $type . '/export-excel'))) ?>">Ekspor</a></p>
<?php if (in_array($type, ['peralatan', 'gedung'], true)): ?>
    <details class="card">
        <summary>Tambah laporan</summary>
        <?php if ($type === 'peralatan'): ?>
            <form method="post" action="<?= site_url('laporan/peralatan/store') ?>">
                <?= csrf_field() ?>
                <?php foreach (['nama' => 'Nama', 'bidang' => 'Bidang', 'nama_alat' => 'Nama alat', 'jenis_alat' => 'Jenis alat', 'kerusakan' => 'Kerusakan'] as $field => $label): ?>
                    <label><?= esc($label) ?></label><input name="<?= esc($field) ?>" required>
                <?php endforeach ?>
                <label>Status</label><select name="status"><option>Menunggu</option><option>Proses</option><option>Selesai</option><option>Ditolak</option></select>
                <label>Prioritas</label><input name="prioritas" required>
                <p><button type="submit">Simpan laporan</button></p>
            </form>
        <?php else: ?>
            <form method="post" action="<?= site_url('laporan/gedung/store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <?php foreach (['nama' => 'Nama', 'telepon' => 'Telepon', 'bidang' => 'Bidang', 'gedung' => 'Gedung', 'lokasi' => 'Lokasi', 'kerusakan' => 'Deskripsi kerusakan'] as $field => $label): ?>
                    <label><?= esc($label) ?></label><input name="<?= esc($field) ?>" required>
                <?php endforeach ?>
                <label>Prioritas</label><input name="prioritas" required>
                <label>Lampiran</label><input name="lampiran" type="file" accept="image/jpeg,image/png">
                <p><button type="submit">Simpan laporan</button></p>
            </form>
        <?php endif ?>
    </details>
<?php endif ?>
<section class="card table-wrap">
    <?php if ($items === []): ?><p class="muted">Belum ada permohonan.</p><?php else: ?>
        <table><thead><tr><th>ID</th><th>Nama</th><th>Ringkasan</th><th>Status</th><th>Tindakan</th></tr></thead><tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['id']) ?></td>
                <td><?= esc($item['nama'] ?? '-') ?></td>
                <td><?= esc($item['nama_alat'] ?? $item['gedung'] ?? $item['jenis_kendaraan'] ?? $item['ruangan'] ?? (isset($item['items']) ? (string) $item['items'] : '-')) ?></td>
                <td><?= esc($item['status'] ?? '-') ?></td>
                <td>
                    <a href="<?= site_url(($type === 'peralatan' || $type === 'gedung' ? 'laporan/' . $type : ($type === 'kendaraan' || $type === 'ruangan' ? 'peminjaman/' . ($type === 'ruangan' ? 'ruang' : $type) : $type)) . '/detail/' . $item['id']) ?>">Detail</a>
                    <form method="post" action="<?= site_url($type === 'bmd' || $type === 'peralatan' || $type === 'gedung' ? ($type === 'bmd' ? 'bmd/update/' : 'laporan/' . $type . '/update/') . $item['id'] : 'admin/update-status/' . $type . '/' . $item['id']) ?>">
                        <?= csrf_field() ?>
                        <select name="status"><?php foreach (['Menunggu', 'Disetujui', 'Proses', 'Selesai', 'Ditolak'] as $status): ?><option <?= $item['status'] === $status ? 'selected' : '' ?>><?= esc($status) ?></option><?php endforeach ?></select>
                        <?php if (in_array($type, ['peralatan', 'gedung'], true)): ?><input name="prioritas" placeholder="Prioritas" value="<?= esc($item['prioritas'] ?? '') ?>"><?php endif ?>
                        <?php if ($type === 'atk'):
                            $requestedItems = json_decode((string) $item['items'], true) ?: [];
                            foreach ($requestedItems as $index => $requestedItem): ?>
                                <label><?= esc($requestedItem['nama_barang'] ?? 'Barang') ?> diberikan</label>
                                <input type="number" min="0" name="items[<?= esc($index) ?>][diberikan]" value="<?= esc($requestedItem['diberikan'] ?? 0) ?>">
                            <?php endforeach;
                        endif ?>
                        <button type="submit">Simpan</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody></table>
    <?php endif ?>
</section>
<?= $this->include('partials/footer') ?>
