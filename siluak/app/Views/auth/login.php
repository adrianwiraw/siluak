<?= $this->include('partials/header', ['title' => 'Login Admin']) ?>
<section class="card" style="max-width:480px;margin:3rem auto">
    <h1>Login Admin</h1>
    <form method="post" action="<?= site_url('login/admin') ?>">
        <?= csrf_field() ?>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= esc(old('email')) ?>" required autocomplete="username">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <p><button type="submit">Masuk</button></p>
    </form>
</section>
<?= $this->include('partials/footer') ?>
