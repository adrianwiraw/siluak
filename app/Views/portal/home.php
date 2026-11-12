<?= $this->include('partials/header', ['title' => 'Portal Layanan']) ?>
<h1>Portal Layanan Umum dan Perlengkapan</h1>
<p class="muted">Ajukan layanan, pelaporan, dan peminjaman melalui formulir di bawah.</p>

<div class="grid">
    <?php
    $services = [
        'bmd' => ['Permohonan BMD', ['nama', 'email', 'bidang', 'kode', 'program', 'kegiatan', 'output', 'keterangan']],
        'alat' => ['Kerusakan Peralatan', ['nama', 'bidang', 'jenis_alat', 'nama_alat', 'kerusakan']],
        'gedung' => ['Kerusakan Gedung', ['nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'deskripsi', 'foto']],
        'kendaraan' => ['Peminjaman Kendaraan', ['nama', 'nip', 'telepon', 'bidang', 'jenis_kendaraan', 'tujuan', 'kegiatan', 'tgl_pinjam', 'tgl_kembali']],
        'ruangan' => ['Peminjaman Ruangan', ['nama', 'nip', 'telepon', 'bidang', 'ruangan', 'acara', 'tanggal', 'waktu_mulai', 'waktu_selesai']],
        'atk' => ['Permintaan ATK', ['nama', 'nip', 'bidang', 'foto']],
    ];
    $labels = [
        'nama' => 'Nama', 'email' => 'Email', 'bidang' => 'Bidang', 'kode' => 'Kode',
        'program' => 'Program', 'kegiatan' => 'Kegiatan', 'output' => 'Output',
        'keterangan' => 'Keterangan', 'jenis_alat' => 'Jenis alat', 'nama_alat' => 'Nama alat',
        'kerusakan' => 'Deskripsi kerusakan', 'telepon' => 'Telepon', 'gedung' => 'Gedung',
        'lokasi' => 'Lokasi', 'deskripsi' => 'Deskripsi', 'nip' => 'NIP',
        'jenis_kendaraan' => 'Jenis kendaraan', 'tujuan' => 'Tujuan',
        'tgl_pinjam' => 'Tanggal pinjam', 'tgl_kembali' => 'Tanggal kembali',
        'ruangan' => 'Ruangan', 'acara' => 'Acara', 'tanggal' => 'Tanggal',
        'waktu_mulai' => 'Waktu mulai', 'waktu_selesai' => 'Waktu selesai',
    ];
    ?>
    <?php foreach ($services as $type => [$name, $fields]): ?>
        <section class="card">
            <h2><?= esc($name) ?></h2>
            <form method="post" action="<?= site_url('layanan/store/' . $type) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <?php foreach ($fields as $field): ?>
                    <?php if ($field === 'foto'): ?>
                        <label for="<?= esc($type . '-' . $field) ?>"><?= esc($type === 'atk' ? 'Foto/nota' : 'Foto pendukung') ?></label>
                        <input id="<?= esc($type . '-' . $field) ?>" type="file" name="foto" accept="image/jpeg,image/png" <?= in_array($type, ['atk', 'gedung'], true) ? 'required' : '' ?>>
                    <?php else: ?>
                        <label for="<?= esc($type . '-' . $field) ?>"><?= esc($labels[$field] ?? ucfirst($field)) ?></label>
                        <?php if (in_array($field, ['keterangan', 'kerusakan', 'deskripsi'], true)): ?>
                            <textarea id="<?= esc($type . '-' . $field) ?>" name="<?= esc($field) ?>" required><?= esc(old($field)) ?></textarea>
                        <?php else: ?>
                            <?php
                            $inputType = match ($field) {
                                'email' => 'email',
                                'tanggal', 'tgl_pinjam', 'tgl_kembali' => 'date',
                                'waktu_mulai', 'waktu_selesai' => 'time',
                                default => 'text',
                            };
                            ?>
                            <input id="<?= esc($type . '-' . $field) ?>" type="<?= $inputType ?>" name="<?= esc($field) ?>" value="<?= esc(old($field)) ?>" <?= $field === 'email' ? 'required' : '' ?>>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
                <?php if (in_array($type, ['bmd', 'atk'], true)): ?>
                    <fieldset><legend>Barang yang diminta</legend>
                        <div class="item-list" data-item-list="<?= esc($type) ?>">
                            <div class="item-row card">
                                <label>Nama barang</label><input name="items[0][nama_barang]" required>
                                <label>Jumlah</label><input type="number" min="1" name="items[0][jumlah]" required>
                                <label>Satuan</label><input name="items[0][satuan]" required>
                            </div>
                        </div>
                        <button type="button" class="add-item" data-item-type="<?= esc($type) ?>">Tambah barang</button>
                    </fieldset>
                <?php endif ?>
                <p><button type="submit">Kirim Permohonan</button></p>
            </form>
            <a href="<?= site_url('umpeg/laporan/' . ($type === 'alat' ? 'kerusakan_alat' : ($type === 'gedung' ? 'kerusakan_gedung' : ($type === 'kendaraan' ? 'pinjam_kendaraan' : ($type === 'ruangan' ? 'pinjam_ruangan' : $type))))) ?>">Lihat laporan</a>
        </section>
    <?php endforeach ?>
</div>

<section class="card">
    <h2>Stok ATK tersedia</h2>
    <?php if ($stockItems === []): ?><p class="muted">Belum ada stok tersedia.</p><?php else: ?>
        <div class="table-wrap"><table><thead><tr><th>Barang</th><th>Kategori</th><th>Stok</th><th>Satuan</th></tr></thead><tbody>
        <?php foreach ($stockItems as $item): ?><tr><td><?= esc($item['nama_barang']) ?></td><td><?= esc($item['kategori']) ?></td><td><?= esc($item['stok']) ?></td><td><?= esc($item['satuan']) ?></td></tr><?php endforeach ?>
        </tbody></table></div>
    <?php endif ?>
</section>
<section class="card">
    <h2>Peminjaman ruangan yang disetujui</h2>
    <?php foreach ($approvedRooms as $room): ?><p><?= esc($room['ruangan']) ?> — <?= esc($room['tanggal']) ?>, <?= esc($room['waktu_mulai']) ?>–<?= esc($room['waktu_selesai']) ?></p><?php endforeach ?>
</section>
<script>
document.querySelectorAll('.add-item').forEach((button) => {
    button.addEventListener('click', () => {
        const type = button.dataset.itemType;
        const list = document.querySelector(`[data-item-list="${type}"]`);
        const index = list.querySelectorAll('.item-row').length;
        const row = list.querySelector('.item-row').cloneNode(true);
        row.querySelectorAll('input').forEach((input) => {
            input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
            input.value = '';
        });
        list.appendChild(row);
    });
});
</script>
<?= $this->include('partials/footer') ?>
