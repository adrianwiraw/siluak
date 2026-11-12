<?= $this->include('partials/header', ['title' => 'Pencarian']) ?>
<h1>Hasil pencarian</h1>
<p>Kata kunci: <strong><?= esc($query) ?></strong></p>
<section class="card table-wrap"><table><thead><tr><th>Jenis</th><th>ID</th><th>Nama</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($results as $result): ?><tr><td><?= esc($result['title']) ?></td><td><?= esc($result['row']['id']) ?></td><td><?= esc($result['row']['nama'] ?? '-') ?></td><td><?= esc($result['row']['status'] ?? '-') ?></td></tr><?php endforeach ?>
</tbody></table></section>
<?= $this->include('partials/footer') ?>
