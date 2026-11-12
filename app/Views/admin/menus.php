<?= $this->include('partials/header', ['title' => 'Menu Layanan']) ?>
<h1>Menu Layanan</h1>
<p><a class="button" href="<?= site_url('admin/layanan/add') ?>">Tambah layanan</a></p>
<section class="card table-wrap"><table><thead><tr><th>Nama</th><th>Slug</th><th>Ikon</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($menus as $menu): ?><tr>
        <td><?= esc($menu['nama_layanan']) ?></td><td><?= esc($menu['slug']) ?></td><td><?= esc($menu['icon']) ?></td><td><?= $menu['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
        <td><form method="post" action="<?= site_url('admin/layanan/toggle/' . $menu['id']) ?>"><?= csrf_field() ?><button type="submit">Ubah status</button></form></td>
    </tr><?php endforeach ?>
</tbody></table></section>
<?= $this->include('partials/footer') ?>
