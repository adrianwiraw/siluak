<?= $this->include('partials/header', ['title' => 'Cek Tiket Permohonan']) ?>
<section class="mx-auto max-w-3xl px-6 py-14 md:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm md:p-10">
        <div class="mb-6 flex size-14 items-center justify-center rounded-2xl bg-blue-50 text-dinas-blue">
            <span class="material-symbols-outlined text-3xl">confirmation_number</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900">Cek Tiket Permohonan</h1>
        <p class="mt-3 leading-relaxed text-slate-600">Masukkan kode tiket yang kamu dapat setelah mengirim permohonan untuk melihat status terbarunya.</p>

        <form class="mt-8 space-y-4" action="<?= site_url('tiket/cari') ?>" method="post">
            <?= csrf_field() ?>
            <label class="block text-sm font-bold text-slate-700" for="ticket-code">Kode tiket</label>
            <input class="block w-full rounded-lg border border-slate-300 px-4 py-3 font-mono uppercase tracking-wide text-slate-900 placeholder:font-sans placeholder:normal-case placeholder:tracking-normal focus:border-primary focus:ring-primary" id="ticket-code" name="kode" type="text" value="<?= esc(old('kode')) ?>" placeholder="Contoh: SLK-ATK-20261112-..." autocomplete="off" required>
            <button class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-dinas-blue px-5 py-3 font-bold text-white transition hover:bg-blue-800 sm:w-auto" type="submit">
                <span class="material-symbols-outlined">search</span>Cek Status
            </button>
        </form>

        <div class="mt-8 rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-600">
            <span class="font-bold text-slate-800">Belum punya tiket?</span>
            Ajukan layanan melalui <a class="font-bold text-dinas-blue hover:underline" href="<?= site_url('/') ?>#layanan">halaman layanan</a>. Kode tiket ditampilkan setelah permohonan berhasil dikirim.
        </div>
    </div>
</section>
<?= $this->include('partials/footer') ?>
