<?php
$uriPath = trim(uri_string(), '/');
$firstSegment = explode('/', $uriPath)[0] ?? '';
$isAdminArea = session()->get('logged_in') && in_array($firstSegment, [
    'admin', 'atk', 'bmd', 'dashboard', 'export', 'laporan', 'manajemen',
    'manajemen-stok', 'peminjaman', 'search',
], true);
?>
<?php if ($isAdminArea): ?>
                </div>
            </main>
        </div>
    </div>
<?php else: ?>
    </main>
    <footer class="mt-auto bg-dinas-blue pb-8 pt-12 text-white">
        <div class="mx-auto max-w-7xl px-6 md:px-12">
            <div class="mb-8 grid grid-cols-1 gap-12 md:grid-cols-3">
                <div>
                    <h3 class="mb-4 text-xl font-bold text-white">Subbag Umum dan Kepegawaian</h3>
                    <p class="text-sm leading-relaxed text-blue-100">Dinas Kearsipan dan Perpustakaan Provinsi Jawa Tengah</p>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-bold text-white">Kontak</h3>
                    <ul class="space-y-3 text-sm text-blue-100">
                        <li class="flex items-start gap-3"><i class="fas fa-envelope mt-1"></i><span>umpeg.arpusjateng@gmail.com</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-phone mt-1"></i><span>08562659297 (Tommy)</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-phone invisible mt-1"></i><span>082138003205 (Niko)</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-bold text-white">Alamat</h3>
                    <ul class="space-y-3 text-sm text-blue-100">
                        <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt mt-1"></i><span>Jl. Setia Budi No.201, Srondol Kulon, Kec. Banyumanik, Kota Semarang, Jawa Tengah</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-blue-500 pt-8 text-center">
                <p class="text-xs text-blue-200">&copy; <?= date('Y') ?> SILUAK - Sistem Layanan Umum dan Kepegawaian Dinas Kearsipan dan Perpustakaan Provinsi Jawa Tengah.</p>
            </div>
        </div>
    </footer>
<?php endif ?>
</body>
</html>
