<?= $this->include('partials/header', ['title' => $item ? 'Ubah Stok' : 'Tambah Stok']) ?>
<h1><?= $item ? 'Ubah Stok ATK' : 'Tambah / Restok ATK' ?></h1>
<section class="card">
    <form method="post" action="<?= site_url($item ? 'manajemen/update/' . $item['id'] : 'manajemen/store') ?>">
        <?= csrf_field() ?>
        <label>Nama barang</label><input name="nama_barang" value="<?= esc(old('nama_barang', $item['nama_barang'] ?? '')) ?>" required>
        <label>Kategori</label><input name="kategori" value="<?= esc(old('kategori', $item['kategori'] ?? '')) ?>" required>
        <label>Satuan</label><input name="satuan" value="<?= esc(old('satuan', $item['satuan'] ?? '')) ?>" required>
        <label>Stok</label><input type="number" min="0" name="stok" value="<?= esc(old('stok', $item['stok'] ?? 0)) ?>" required>
        <label>Stok minimal</label><input type="number" min="0" name="stok_minimal" value="<?= esc(old('stok_minimal', $item['stok_minimal'] ?? 10)) ?>" required>
        <p><button type="submit">Simpan</button></p>
    </form>
</section>
<?= $this->include('partials/footer') ?>
