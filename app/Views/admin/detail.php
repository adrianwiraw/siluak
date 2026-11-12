<?= $this->include('partials/header', ['title' => $title]) ?>
<h1><?= esc($title) ?> #<?= esc($item['id']) ?></h1>
<p><a class="button" href="<?= site_url('dashboard') ?>">Dashboard</a></p>
<section class="card table-wrap"><table><tbody>
    <?php foreach ($item as $field => $value): ?>
        <tr><th><?= esc(ucwords(str_replace('_', ' ', $field))) ?></th><td>
            <?php if (in_array($field, ['foto', 'surat_permohonan'], true) && is_string($value) && $value !== ''): ?>
                <a href="<?= site_url('media/' . $value) ?>" target="_blank" rel="noopener">Lihat / unduh file</a>
                <?php if ($field === 'foto'): ?><br><img src="<?= site_url('media/' . $value) ?>" alt="Lampiran" style="max-width:320px;max-height:240px"><?php endif ?>
            <?php else: ?>
                <?= esc(is_scalar($value) || $value === null ? (string) $value : json_encode($value)) ?>
            <?php endif ?>
        </td></tr>
    <?php endforeach ?>
</tbody></table></section>
<?= $this->include('partials/footer') ?>
