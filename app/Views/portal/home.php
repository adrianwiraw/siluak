<?= $this->include('partials/header', ['title' => 'Portal Layanan']) ?>

<section class="relative w-full overflow-hidden bg-dinas-blue px-6 py-10 text-center text-white">
    <div class="pointer-events-none absolute inset-0 opacity-10" style="background-image:url('https://www.transparenttextures.com/patterns/cubes.png')"></div>
    <div class="relative z-10 mx-auto mt-10 max-w-4xl">
        <h1 class="mb-4 text-4xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">SILUAK</h1>
        <p class="mb-10 text-lg font-light text-blue-100 md:text-xl">Sistem Layanan Umum dan Kepegawaian</p>
        <div class="mb-12 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/20 px-6 py-3 shadow-lg backdrop-blur-md">
            <span class="font-medium tracking-wide">Bekerja dengan Cinta, Melayani dengan Integritas</span>
        </div>
    </div>
</section>

<section id="tugas" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-16">
    <div class="grid grid-cols-1 gap-6 text-center md:grid-cols-4">
        <?php foreach ([
            ['file-alt', 'Administrasi Umum', 'Mengelola administrasi surat menyurat, kearsipan, dan dokumentasi kegiatan dinas.'],
            ['users', 'Kepegawaian', 'Mengelola data kepegawaian, absensi, cuti, dan pengembangan SDM aparatur.'],
            ['building', 'Pengelolaan Aset', 'Mengelola inventaris barang milik daerah (BMD), pemeliharaan gedung dan peralatan kantor.'],
            ['briefcase', 'Layanan Publik', 'Menyediakan layanan pinjam kendaraan, ruang rapat, ATK, dan layanan umum lainnya.'],
        ] as [$icon, $title, $description]): ?>
            <div class="cursor-default rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-dinas-blue text-white shadow-sm">
                    <i class="fas fa-<?= esc($icon) ?> text-xl"></i>
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900"><?= esc($title) ?></h3>
                <p class="text-sm leading-relaxed text-gray-600"><?= esc($description) ?></p>
            </div>
        <?php endforeach ?>
    </div>
</section>

<?php
$services = [
    'bmd' => [
        'name' => 'Usulan Rencana Kebutuhan BMD',
        'description' => 'Pengajuan rencana kebutuhan barang milik daerah untuk mendukung operasional dinas.',
        'icon' => 'clipboard-list',
        'button' => 'Ajukan Permohonan',
        'buttonId' => 'btnAjukanPermohonan',
        'modalId' => 'requestModal',
        'formTitle' => 'Formulir Usulan Rencana Kebutuhan BMD',
        'fields' => ['nama', 'email', 'bidang', 'kode', 'program', 'kegiatan', 'output', 'keterangan'],
    ],
    'alat' => [
        'name' => 'Pelaporan Kerusakan Peralatan Kantor',
        'description' => 'Melaporkan kerusakan peralatan kantor untuk segera ditindaklanjuti perbaikan.',
        'icon' => 'exclamation-triangle',
        'button' => 'Laporkan Kerusakan Alat',
        'buttonId' => 'btnLaporAlat',
        'modalId' => 'modalKerusakanAlat',
        'formTitle' => 'Formulir Pelaporan Kerusakan Peralatan Kantor',
        'fields' => ['nama', 'bidang', 'jenis_alat', 'nama_alat', 'kerusakan'],
    ],
    'gedung' => [
        'name' => 'Pelaporan Kerusakan Gedung & Bangunan',
        'description' => 'Melaporkan kerusakan infrastruktur gedung dan bangunan kantor.',
        'icon' => 'building',
        'button' => 'Laporkan Kerusakan Gedung',
        'buttonId' => 'btnLaporGedung',
        'modalId' => 'modalKerusakanGedung',
        'formTitle' => 'Formulir Pelaporan Kerusakan Gedung',
        'fields' => ['nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'foto', 'deskripsi'],
    ],
    'kendaraan' => [
        'name' => 'Peminjaman Kendaraan Dinas',
        'description' => 'Peminjaman kendaraan dinas untuk keperluan kegiatan resmi.',
        'icon' => 'car',
        'button' => 'Ajukan Peminjaman Kendaraan',
        'buttonId' => 'btnPinjamKendaraan',
        'modalId' => 'modalPinjamKendaraan',
        'formTitle' => 'Formulir Peminjaman Kendaraan Dinas',
        'fields' => ['nama', 'nip', 'telepon', 'bidang', 'jenis_kendaraan', 'tujuan', 'kegiatan', 'tgl_pinjam', 'tgl_kembali'],
    ],
    'ruangan' => [
        'name' => 'Peminjaman Ruangan Rapat',
        'description' => 'Peminjaman ruang rapat untuk kegiatan pertemuan dan koordinasi.',
        'icon' => 'door-open',
        'button' => 'Ajukan Peminjaman Ruang',
        'buttonId' => 'btnPinjamRuangan',
        'modalId' => 'modalPinjamRuangan',
        'formTitle' => 'Formulir Peminjaman Ruangan Rapat',
        'fields' => ['nama', 'nip', 'telepon', 'bidang', 'ruangan', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'acara'],
    ],
    'atk' => [
        'name' => 'Permohonan ATK',
        'description' => 'Layanan pengajuan kebutuhan alat tulis kantor (ATK) untuk mendukung kegiatan operasional dinas.',
        'icon' => 'book-reader',
        'button' => 'Ajukan Permohonan',
        'buttonId' => 'btnDaftarATK',
        'modalId' => 'modalATK',
        'formTitle' => 'Formulir Permohonan Alat Tulis Kantor (ATK)',
        'fields' => ['nama', 'nip', 'bidang', 'foto'],
    ],
];

$fieldLabels = [
    'nama' => 'Nama Lengkap',
    'email' => 'Email',
    'bidang' => 'Bidang',
    'kode' => 'Kode',
    'program' => 'Program',
    'kegiatan' => 'Kegiatan',
    'output' => 'Output',
    'keterangan' => 'Keterangan',
    'jenis_alat' => 'Jenis Peralatan',
    'nama_alat' => 'Nama Barang (Merk, Type)',
    'kerusakan' => 'Deskripsi Kerusakan',
    'telepon' => 'No. Telepon/WA',
    'gedung' => 'Nama Gedung/Bangunan',
    'lokasi' => 'Lokasi Kerusakan',
    'deskripsi' => 'Deskripsi Kerusakan',
    'nip' => 'NIP',
    'jenis_kendaraan' => 'Jenis Kendaraan',
    'tujuan' => 'Tujuan Kabupaten/Kota',
    'tgl_pinjam' => 'Tanggal Pinjam',
    'tgl_kembali' => 'Tanggal Kembali',
    'ruangan' => 'Daftar Ruang Rapat',
    'tanggal' => 'Tanggal Pinjam',
    'waktu_mulai' => 'Waktu Mulai',
    'waktu_selesai' => 'Waktu Selesai',
    'acara' => 'Acara',
    'foto' => 'Dokumentasi Pendukung (Maks 1MB)',
];
$fieldOptions = [
    'bidang' => [
        'Umum dan Kepegawaian (Sekretariat)',
        'Keuangan (Sekretariat)',
        'Program (Sekretariat)',
        'LPA',
        'PPA',
        'P3K',
        'Pengembangan Perpustakaan',
        'Pengelolaan Perpustakaan',
    ],
    'jenis_alat' => ['Komputer', 'Laptop', 'Printer', 'Lainnya (Tulis di Nama Barang)'],
    'gedung' => [
        'Gedung A Sekretariat',
        'Gedung B Depot Arsip Statis (Depo Selatan)',
        'Gedung C Depot Arsip Inaktif (Depo Barat)',
        'Gedung D Pengolahan Arsip',
        'Gedung A Perpustakaan',
        'Gedung B Perpustakaan',
        'Lainnya (Tulis di Lokasi)',
    ],
    'jenis_kendaraan' => ['Mobil', 'Motor'],
    'ruangan' => [
        'Ruang Rapat Lantai 1-Dinas Srondol',
        'Ruang Rapat Lantai 2-Dinas Srondol',
        'Ruang Rapat Lantai 4-Dinas Srondol',
        'Ruang Rapat Lantai 3-Perpustakaan',
        'Ruang Rapat Lantai 4-Perpustakaan',
    ],
];
$roomDateFormatter = new \IntlDateFormatter(
    'id_ID',
    \IntlDateFormatter::FULL,
    \IntlDateFormatter::NONE,
    'Asia/Jakarta',
    \IntlDateFormatter::GREGORIAN,
    'EEEE, dd MMMM yyyy'
);
?>

<section id="layanan" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-6">
    <div class="mb-12 text-center">
        <h2 class="text-3xl font-bold text-dinas-blue md:text-4xl">Informasi Layanan</h2>
    </div>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($services as $type => $service): ?>
            <div class="flex h-full flex-col rounded-xl border border-blue-100 bg-blue-50 p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="mb-6 flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-dinas-blue text-white">
                        <i class="fas fa-<?= esc($service['icon']) ?> text-xl"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 text-lg font-bold text-gray-900"><?= esc($service['name']) ?></h3>
                        <p class="text-sm leading-relaxed text-gray-600"><?= esc($service['description']) ?></p>
                    </div>
                </div>
                <div class="mt-auto">
                    <button id="<?= esc($service['buttonId']) ?>" class="flex w-full items-center justify-center gap-2 rounded-lg bg-dinas-blue px-4 py-2.5 font-semibold text-white transition-colors hover:bg-blue-700" type="button">
                        <i class="fas fa-plus text-sm"></i><?= esc($service['button']) ?>
                    </button>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>

<section id="jadwal" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-16">
    <div class="mb-12 text-center">
        <h2 class="mb-4 text-3xl font-bold text-dinas-blue md:text-4xl">Jadwal Pemakaian Ruang Rapat</h2>
        <p class="mx-auto mb-6 max-w-3xl text-gray-600">Daftar reservasi ruangan yang telah diajukan. Pastikan untuk mengecek ketersediaan sebelum melakukan peminjaman.</p>
    </div>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <?php if ($approvedRooms === []): ?>
            <p class="col-span-full text-center text-gray-500">Belum ada jadwal peminjaman ruangan saat ini.</p>
        <?php else: ?>
            <?php foreach ($approvedRooms as $room): ?>
                <article class="flex h-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-lg">
                    <div class="flex h-32 flex-col items-center justify-center bg-dinas-blue p-4 text-center text-white">
                        <span class="text-sm font-medium uppercase tracking-wider opacity-80">Ruangan</span>
                        <h3 class="font-bold leading-tight text-white"><?= esc($room['ruangan']) ?></h3>
                    </div>
                    <div class="flex-1 p-6">
                        <div class="mb-3 flex items-center gap-2 text-sm font-bold text-dinas-blue">
                            <i class="far fa-clock"></i>
                            <span><?= esc($roomDateFormatter->format(strtotime((string) $room['tanggal']))) ?></span>
                        </div>
                        <div class="mb-3 inline-block rounded bg-gray-100 px-2 py-1 text-xs text-gray-500">
                            Pukul: <?= esc($room['waktu_mulai']) ?> - <?= esc($room['waktu_selesai']) ?> WIB
                        </div>
                        <h3 class="mb-2 text-lg font-bold leading-tight text-gray-900"><?= esc($room['acara']) ?></h3>
                        <div class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-user text-blue-500"></i><span class="font-medium"><?= esc($room['nama']) ?></span></div>
                            <div class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-building text-gray-400"></i><span><?= esc($room['bidang']) ?></span></div>
                        </div>
                    </div>
                </article>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</section>

<?php foreach ($services as $type => $service): ?>
    <section id="<?= esc($service['modalId']) ?>" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="<?= esc($service['modalId']) ?>-title">
        <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-dinas-blue px-6 py-4">
                <h2 id="<?= esc($service['modalId']) ?>-title" class="text-xl font-bold text-white"><?= esc($service['formTitle']) ?></h2>
                <button class="modal-close text-white hover:text-gray-200 focus:outline-none" data-modal-close="<?= esc($service['modalId']) ?>" type="button" aria-label="Tutup">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="max-h-[85vh] overflow-y-auto p-6">
                <form action="<?= site_url('layanan/store/' . $type) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($type === 'alat'): ?>
                        <input type="hidden" name="status" value="Menunggu">
                        <input type="hidden" name="prioritas" value="Belum Diatur">
                    <?php endif ?>
                    <div class="mb-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <?php foreach ($service['fields'] as $field): ?>
                            <?php
                            $inputId = $type . '-' . $field;
                            $fieldType = match ($field) {
                                'email' => 'email',
                                'telepon' => 'tel',
                                'nip' => 'number',
                                'tanggal', 'tgl_pinjam', 'tgl_kembali' => 'date',
                                'waktu_mulai', 'waktu_selesai' => 'time',
                                default => 'text',
                            };
                            $wideField = in_array($field, ['kegiatan', 'keterangan', 'kerusakan', 'deskripsi'], true);
                            $requiredField = ! ($type === 'bmd' && $field === 'kode');
                            ?>
                            <div class="<?= $wideField ? 'md:col-span-2' : '' ?>">
                                <label class="mb-1 block text-sm font-bold text-gray-700" for="<?= esc($inputId) ?>">
                                    <?= esc($fieldLabels[$field] ?? ucfirst($field)) ?><?= $requiredField ? ' *' : '' ?>
                                </label>
                                <?php if ($field === 'foto'): ?>
                                    <input id="<?= esc($inputId) ?>" class="w-full rounded-md border px-3 py-2 focus:border-blue-500 focus:ring-blue-500" type="file" name="foto" accept="image/jpeg,image/png" <?= $requiredField ? 'required' : '' ?> onchange="validateFileSize(this, 1)">
                                    <small class="mt-1 block text-xs text-gray-500">Format: JPG, PNG</small>
                                <?php elseif (isset($fieldOptions[$field])): ?>
                                    <select id="<?= esc($inputId) ?>" class="w-full rounded-md border px-3 py-2 focus:border-blue-500 focus:ring-blue-500" name="<?= esc($field) ?>" required>
                                        <option value="">Pilih <?= esc($fieldLabels[$field] ?? $field) ?></option>
                                        <?php foreach ($fieldOptions[$field] as $option): ?>
                                            <option value="<?= esc($option) ?>" <?= old($field) === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php elseif (in_array($field, ['keterangan', 'kerusakan', 'deskripsi'], true)): ?>
                                    <textarea id="<?= esc($inputId) ?>" class="w-full rounded-md border px-3 py-2 focus:border-blue-500 focus:ring-blue-500" name="<?= esc($field) ?>" rows="2" <?= $requiredField ? 'required' : '' ?>><?= esc(old($field)) ?></textarea>
                                <?php else: ?>
                                    <input id="<?= esc($inputId) ?>" class="w-full rounded-md border px-3 py-2 focus:border-blue-500 focus:ring-blue-500" type="<?= $fieldType ?>" name="<?= esc($field) ?>" value="<?= esc(old($field)) ?>" <?= $requiredField ? 'required' : '' ?>>
                                <?php endif ?>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <?php if (in_array($type, ['bmd', 'atk'], true)): ?>
                        <hr class="mb-6">
                        <div class="mb-4">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <h3 class="font-bold text-gray-800">Daftar Barang yang Diminta</h3>
                                <button class="add-item-row flex items-center gap-1 rounded-md bg-dinas-blue px-4 py-2 text-sm text-white shadow-sm transition-all hover:bg-blue-700" data-item-type="<?= esc($type) ?>" type="button">
                                    <span class="material-symbols-outlined text-base">add</span>Tambah Barang
                                </button>
                            </div>
                            <div class="space-y-4" data-item-list="<?= esc($type) ?>">
                                <div class="item-row grid grid-cols-1 items-end gap-4 rounded-lg border bg-gray-50 p-4 shadow-sm md:grid-cols-10">
                                    <div class="md:col-span-4">
                                        <label class="mb-1 block text-xs font-bold uppercase text-gray-600" for="<?= esc($type) ?>-item-name-0"><?= $type === 'atk' ? 'Pilih Barang *' : 'Nama Barang *' ?></label>
                                        <?php if ($type === 'atk'): ?>
                                            <select id="atk-item-name-0" class="w-full rounded-md border px-3 py-2 text-sm focus:ring-blue-500" name="items[0][nama_barang]" required>
                                                <option value="">Pilih Barang</option>
                                                <?php foreach ($stockItems as $stockItem): ?>
                                                    <option value="<?= esc($stockItem['nama_barang']) ?>"><?= esc($stockItem['nama_barang']) ?> (Sisa: <?= esc($stockItem['stok']) ?> <?= esc($stockItem['satuan']) ?>)</option>
                                                <?php endforeach ?>
                                            </select>
                                        <?php else: ?>
                                            <input id="bmd-item-name-0" class="w-full rounded-md border px-3 py-2 text-sm" name="items[0][nama_barang]" required>
                                        <?php endif ?>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="mb-1 block text-xs font-bold uppercase text-gray-600" for="<?= esc($type) ?>-item-quantity-0">Jumlah *</label>
                                        <input id="<?= esc($type) ?>-item-quantity-0" class="w-full rounded-md border px-3 py-2 text-sm" type="number" min="1" name="items[0][jumlah]" placeholder="0" required>
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="mb-1 block text-xs font-bold uppercase text-gray-600" for="<?= esc($type) ?>-item-unit-0">Satuan *</label>
                                        <select id="<?= esc($type) ?>-item-unit-0" class="w-full rounded-md border px-3 py-2 text-sm" name="items[0][satuan]" required>
                                            <option value="">Pilih Satuan</option>
                                            <?php foreach (['Rim', 'Pcs', 'Lusin', 'Pack', 'Unit'] as $unit): ?><option value="<?= esc($unit) ?>"><?= esc($unit) ?></option><?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="flex justify-center pb-2 md:col-span-1">
                                        <button class="remove-item-row border-0 bg-transparent p-0 text-red-400 transition-colors hover:bg-transparent hover:text-red-600" type="button" aria-label="Hapus barang">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-6">
                        <button class="flex-1 rounded-lg bg-dinas-blue py-3 font-bold text-white shadow-md transition-all hover:bg-blue-700" type="submit">Kirim Permohonan</button>
                        <button class="modal-cancel rounded-lg border border-gray-300 px-8 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-50" data-modal-close="<?= esc($service['modalId']) ?>" type="button">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php endforeach ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalByButton = {
        btnAjukanPermohonan: 'requestModal',
        btnLaporAlat: 'modalKerusakanAlat',
        btnLaporGedung: 'modalKerusakanGedung',
        btnPinjamKendaraan: 'modalPinjamKendaraan',
        btnPinjamRuangan: 'modalPinjamRuangan',
        btnDaftarATK: 'modalATK',
    };

    Object.entries(modalByButton).forEach(([buttonId, modalId]) => {
        document.getElementById(buttonId)?.addEventListener('click', () => {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.modalClose);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });

    document.querySelectorAll('.add-item-row').forEach((button) => {
        button.addEventListener('click', () => {
            const type = button.dataset.itemType;
            const list = document.querySelector(`[data-item-list="${type}"]`);
            const index = list.querySelectorAll('.item-row').length;
            const row = list.querySelector('.item-row').cloneNode(true);
            row.querySelectorAll('input, select').forEach((field) => {
                field.name = field.name.replace(/items\[\d+\]/, `items[${index}]`);
                field.id = field.id.replace(/-\d+$/, `-${index}`);
                if (field.tagName === 'SELECT') {
                    field.selectedIndex = 0;
                } else {
                    field.value = '';
                }
            });
            row.querySelectorAll('label[for]').forEach((label) => {
                label.htmlFor = label.htmlFor.replace(/-\d+$/, `-${index}`);
            });
            list.appendChild(row);
        });
    });

    document.querySelectorAll('[data-item-list]').forEach((list) => {
        list.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.remove-item-row');
            if (removeButton && list.querySelectorAll('.item-row').length > 1) {
                removeButton.closest('.item-row').remove();
            }
        });
    });

    document.querySelectorAll('[role="dialog"]').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('[role="dialog"]:not(.hidden)').forEach((modal) => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }
    });
});

function validateFileSize(input, maxSizeMB) {
    const file = input.files[0];
    if (file && file.size > maxSizeMB * 1024 * 1024) {
        alert(`Ukuran file maksimal adalah ${maxSizeMB}MB`);
        input.value = '';
    }
}
</script>

<?= $this->include('partials/footer') ?>
