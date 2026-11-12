<?= $this->include('partials/header', ['title' => 'Tambah Layanan']) ?>
<h1>Tambah layanan</h1>
<section class="card"><form method="post" action="<?= site_url('admin/layanan/store') ?>">
    <?= csrf_field() ?>
    <label>Nama layanan</label><input name="nama_layanan" required>
    <label>Ikon</label><input name="icon" value="description">
    <p><button type="submit">Simpan</button></p>
</form></section>
<?= $this->include('partials/footer') ?>
