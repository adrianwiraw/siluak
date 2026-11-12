<?= $this->include('partials/header', ['title' => $title]) ?>
<h1><?= esc($title) ?></h1>
<p><a class="button" href="<?= site_url('/') ?>">Kembali ke portal</a></p>
<section class="card table-wrap">
    <?php if ($items === []): ?><p class="muted">Belum ada data.</p><?php else: ?>
        <table>
            <thead><tr><?php foreach (array_keys($items[0]) as $field): ?><th><?= esc(ucwords(str_replace('_', ' ', $field))) ?></th><?php endforeach ?><?php if ($type === 'kendaraan'): ?><th>Tindakan</th><?php endif ?></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr>
                <?php foreach ($item as $field => $value): ?>
                    <td><?= esc($field === 'items' ? (is_string($value) ? $value : json_encode($value)) : (string) $value) ?></td>
                <?php endforeach ?>
                <?php if ($type === 'kendaraan'): ?>
                    <td>
                        <a href="<?= site_url('umpeg/laporan/pinjam_kendaraan/download/' . $item['id']) ?>">Unduh template</a>
                        <form method="post" action="<?= site_url('umpeg/laporan/pinjam_kendaraan/upload/' . $item['id']) ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <label>Surat permohonan</label><input type="file" name="surat" accept=".pdf,.doc,.docx" required>
                            <button type="submit">Unggah</button>
                        </form>
                    </td>
                <?php endif ?>
            </tr><?php endforeach ?></tbody>
        </table>
    <?php endif ?>
</section>
<?= $this->include('partials/footer') ?>
