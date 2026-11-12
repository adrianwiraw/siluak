<?= $this->include('partials/header', ['title' => 'Manajemen Stok']) ?>
<h1>Manajemen Stok ATK</h1>
<p>Total jenis: <?= esc($totalTypes) ?> · Stok menipis: <?= esc($lowStock) ?> · Total unit: <?= esc($totalUnits) ?></p>
<p><a class="button" href="<?= site_url('manajemen/create') ?>">Tambah / Restok</a>
    <a class="button" href="<?= site_url('manajemen/export-excel') ?>">Ekspor</a></p>
<section class="card table-wrap"><table><thead><tr><th>Barang</th><th>Kategori</th><th>Jumlah</th><th>Satuan</th><th>Stok minimal</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($items as $item): ?><tr>
        <td><?= esc($item['nama_barang']) ?></td><td><?= esc($item['kategori']) ?></td><td><?= esc($item['stok']) ?></td><td><?= esc($item['satuan']) ?></td><td><?= esc($item['stok_minimal']) ?></td>
        <td><a href="<?= site_url('manajemen/detail/' . $item['id']) ?>">Ubah</a>
            <form method="post" action="<?= site_url('manajemen/destroy/' . $item['id']) ?>"><?= csrf_field() ?><input type="hidden" name="_method" value="DELETE"><button class="danger">Hapus</button></form>
        </td>
    </tr><?php endforeach ?>
</tbody></table></section>
<?= $this->include('partials/footer') ?>
