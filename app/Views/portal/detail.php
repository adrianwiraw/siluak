<?= $this->include('partials/header', ['title' => 'Detail Permohonan']) ?>
<h1>Detail permohonan <?= esc(ucfirst($type)) ?></h1>
<section class="card">
    <dl>
        <?php foreach ($record as $field => $value): ?>
            <dt><strong><?= esc(ucwords(str_replace('_', ' ', $field))) ?></strong></dt>
            <dd><?= esc(is_scalar($value) || $value === null ? (string) $value : json_encode($value)) ?></dd>
        <?php endforeach ?>
    </dl>
</section>
<?= $this->include('partials/footer') ?>
