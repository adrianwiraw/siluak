<?php

namespace App\Controllers;

use App\Models\AtkModel;
use App\Models\KerusakanAlatModel;
use App\Models\KerusakanGedungModel;
use App\Models\PengajuanBmdModel;
use App\Models\PeminjamanKendaraanModel;
use App\Models\PeminjamanRuanganModel;
use CodeIgniter\Model;
use CodeIgniter\HTTP\Files\UploadedFile;

class SubmissionController extends BaseController
{
    private const TYPES = [
        'bmd' => [
            'model' => PengajuanBmdModel::class,
            'title' => 'Permohonan BMD',
            'fields' => ['nama', 'email', 'bidang', 'kode', 'program', 'kegiatan', 'output', 'keterangan', 'items'],
        ],
        'alat' => [
            'model' => KerusakanAlatModel::class,
            'title' => 'Kerusakan Peralatan',
            'fields' => ['nama', 'bidang', 'jenis_alat', 'nama_alat', 'kerusakan'],
        ],
        'gedung' => [
            'model' => KerusakanGedungModel::class,
            'title' => 'Kerusakan Gedung',
            'fields' => ['nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'deskripsi', 'foto'],
        ],
        'kendaraan' => [
            'model' => PeminjamanKendaraanModel::class,
            'title' => 'Peminjaman Kendaraan',
            'fields' => ['nama', 'nip', 'telepon', 'bidang', 'jenis_kendaraan', 'tujuan', 'kegiatan', 'tgl_pinjam', 'tgl_kembali'],
        ],
        'ruangan' => [
            'model' => PeminjamanRuanganModel::class,
            'title' => 'Peminjaman Ruangan',
            'fields' => ['nama', 'nip', 'telepon', 'bidang', 'ruangan', 'acara', 'tanggal', 'waktu_mulai', 'waktu_selesai'],
        ],
        'atk' => [
            'model' => AtkModel::class,
            'title' => 'Permintaan ATK',
            'fields' => ['nama', 'nip', 'bidang', 'items', 'foto'],
        ],
    ];

    private const TICKET_PREFIXES = [
        'bmd' => 'BMD',
        'alat' => 'ALAT',
        'gedung' => 'GEDUNG',
        'kendaraan' => 'KENDARAAN',
        'ruangan' => 'RUANGAN',
        'atk' => 'ATK',
    ];

    public function listing(string $type)
    {
        $type = $this->normalizeType($type);
        if ($type === null) {
            return redirect()->to(site_url(''))->with('error', 'Jenis laporan tidak dikenal.');
        }

        $definition = self::TYPES[$type];
        $modelClass = $definition['model'];
        $items = (new $modelClass())->orderBy('created_at', 'DESC')->findAll();

        return view('portal/report_list', [
            'type' => $type,
            'title' => $definition['title'],
            'items' => $items,
        ]);
    }

    public function store(string $type)
    {
        $type = $this->normalizeType($type);
        if ($type === null) {
            return redirect()->back()->with('error', 'Jenis layanan tidak valid.');
        }

        $data = $this->request->getPost();
        $errors = $this->validateSubmission($type, $data);
        if ($errors !== []) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        if (in_array($type, ['bmd', 'atk'], true)) {
            $data['items'] = json_encode($data['items'], JSON_THROW_ON_ERROR);
        }

        if ($type === 'alat') {
            $data['status'] = 'Menunggu';
        }

        if ($type === 'gedung') {
            $data['foto'] = $this->storeUpload('foto', 'pelaporan/gedung', ['image/jpeg', 'image/png']);
            if ($this->request->getFile('foto')?->isValid() && $data['foto'] === null) {
                return redirect()->back()->withInput()->with('error', 'Foto gagal diunggah.');
            }
        }

        if ($type === 'atk') {
            $data['foto'] = $this->storeUpload('foto', 'pengajuan/atk', ['image/jpeg', 'image/png']);
            if ($data['foto'] === null) {
                return redirect()->back()->withInput()->with('error', 'Foto/nota ATK wajib diunggah.');
            }
        }

        $allowed = self::TYPES[$type]['fields'];
        $record = array_intersect_key($data, array_flip($allowed));
        if ($type === 'gedung' && isset($record['deskripsi'])) {
            $record['status'] = 'Menunggu';
        }

        $modelClass = self::TYPES[$type]['model'];
        $model = new $modelClass();
        $ticketCode = $this->generateTicketCode($type, $model);
        $record['ticket_code'] = $ticketCode;
        if ($model->insert($record) === false) {
            return redirect()->back()->withInput()->with('error', 'Permohonan gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->to(site_url('tiket/' . $ticketCode));
    }

    public function adminStoreEquipment()
    {
        $data = $this->request->getPost();
        $required = ['nama', 'bidang', 'nama_alat', 'jenis_alat', 'kerusakan', 'status', 'prioritas'];
        foreach ($required as $field) {
            if (trim((string) ($data[$field] ?? '')) === '') {
                return redirect()->back()->withInput()->with('error', 'Lengkapi semua data laporan peralatan.');
            }
        }

        (new KerusakanAlatModel())->insert(array_intersect_key($data, array_flip($required)));

        return redirect()->back()->with('success', 'Laporan peralatan berhasil dibuat.');
    }

    public function adminStoreBuilding()
    {
        $data = $this->request->getPost();
        $required = ['nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'kerusakan', 'prioritas'];
        foreach ($required as $field) {
            if (trim((string) ($data[$field] ?? '')) === '') {
                return redirect()->back()->withInput()->with('error', 'Lengkapi semua data laporan gedung.');
            }
        }

        $foto = $this->storeUpload('lampiran', 'pelaporan/gedung', ['image/jpeg', 'image/png'], 5);
        $file = $this->request->getFile('lampiran');
        if ($file !== null && $file->isValid() && $foto === null) {
            return redirect()->back()->withInput()->with('error', 'Lampiran harus berupa gambar maksimal 5 MB.');
        }

        (new KerusakanGedungModel())->insert([
            'nama' => $data['nama'],
            'telepon' => $data['telepon'],
            'bidang' => $data['bidang'],
            'gedung' => $data['gedung'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => $data['kerusakan'],
            'foto' => $foto,
            'prioritas' => $data['prioritas'],
            'status' => 'Menunggu',
        ]);

        return redirect()->back()->with('success', 'Laporan gedung berhasil dibuat.');
    }

    public function downloadVehicleTemplate(int $id)
    {
        $path = ROOTPATH . 'public/templates/template_izin_kendaraan.docx';
        if (! is_file($path)) {
            return redirect()->back()->with('error', 'Template surat izin kendaraan tidak ditemukan.');
        }

        return $this->response->download($path, null)->setFileName('Form_Permohonan_Izin_Kendaraan.docx');
    }

    public function uploadVehicleLetter(int $id)
    {
        $model = new PeminjamanKendaraanModel();
        $record = $model->find($id);
        if ($record === null) {
            return redirect()->back()->with('error', 'Permohonan kendaraan tidak ditemukan.');
        }

        $file = $this->request->getFile('surat');
        if ($file === null || ! $file->isValid() || $file->getSizeByUnit('mb') > 2
            || ! in_array($file->getMimeType(), ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], true)) {
            return redirect()->back()->with('error', 'Surat harus berupa PDF/DOC/DOCX maksimal 2 MB.');
        }

        $path = $this->storeFile($file, 'surat_peminjaman');
        if ($record['surat_permohonan'] !== null) {
            $oldPath = WRITEPATH . $record['surat_permohonan'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        $model->update($id, ['surat_permohonan' => $path]);

        return redirect()->back()->with('success', 'Surat permohonan berhasil diunggah.');
    }

    private function normalizeType(string $type): ?string
    {
        $aliases = [
            'kerusakan_alat' => 'alat',
            'peralatan' => 'alat',
            'kerusakan_gedung' => 'gedung',
            'pinjam_kendaraan' => 'kendaraan',
            'pinjam_ruangan' => 'ruangan',
        ];
        $type = $aliases[$type] ?? $type;

        return isset(self::TYPES[$type]) ? $type : null;
    }

    private function generateTicketCode(string $type, Model $model): string
    {
        $prefix = self::TICKET_PREFIXES[$type];
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $code = sprintf('SLK-%s-%s-%s', $prefix, date('Ymd'), strtoupper(bin2hex(random_bytes(8))));
            if ($model->where('ticket_code', $code)->first() === null) {
                return $code;
            }
        }

        throw new \RuntimeException('Tidak dapat membuat kode tiket unik setelah beberapa percobaan.');
    }

    private function validateSubmission(string $type, array $data): array
    {
        $required = [
            'bmd' => ['nama', 'email', 'bidang', 'program', 'kegiatan', 'output', 'keterangan'],
            'alat' => ['nama', 'bidang', 'jenis_alat', 'nama_alat', 'kerusakan'],
            'gedung' => ['nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'deskripsi'],
            'kendaraan' => ['nama', 'nip', 'telepon', 'bidang', 'jenis_kendaraan', 'tujuan', 'kegiatan', 'tgl_pinjam', 'tgl_kembali'],
            'ruangan' => ['nama', 'nip', 'telepon', 'bidang', 'ruangan', 'acara', 'tanggal', 'waktu_mulai', 'waktu_selesai'],
            'atk' => ['nama', 'nip', 'bidang'],
        ][$type];
        $errors = [];

        foreach ($required as $field) {
            if (! isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
            }
        }

        if ($type === 'bmd' && (! filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL))) {
            $errors['email'] = 'Email tidak valid.';
        }
        if (in_array($type, ['bmd', 'atk'], true) && (! isset($data['items']) || ! is_array($data['items']) || $data['items'] === [])) {
            $errors['items'] = 'Minimal satu barang harus ditambahkan.';
        } elseif (in_array($type, ['bmd', 'atk'], true)) {
            foreach ($data['items'] as $index => $item) {
                if (! is_array($item)
                    || trim((string) ($item['nama_barang'] ?? '')) === ''
                    || trim((string) ($item['satuan'] ?? '')) === ''
                    || filter_var($item['jumlah'] ?? null, FILTER_VALIDATE_INT) === false
                    || (int) ($item['jumlah'] ?? 0) < 1) {
                    $errors['items.' . $index] = 'Setiap barang harus memiliki nama, jumlah minimal satu, dan satuan.';
                }
            }
        }
        foreach (['tgl_pinjam', 'tgl_kembali', 'tanggal'] as $dateField) {
            if (isset($data[$dateField]) && $data[$dateField] !== '' && strtotime((string) $data[$dateField]) === false) {
                $errors[$dateField] = 'Format tanggal tidak valid.';
            }
        }
        if ($type === 'kendaraan' && isset($data['tgl_pinjam'], $data['tgl_kembali']) && $data['tgl_kembali'] < $data['tgl_pinjam']) {
            $errors['tgl_kembali'] = 'Tanggal kembali harus sama atau setelah tanggal pinjam.';
        }
        if ($type === 'ruangan' && isset($data['waktu_mulai'], $data['waktu_selesai']) && $data['waktu_selesai'] <= $data['waktu_mulai']) {
            $errors['waktu_selesai'] = 'Waktu selesai harus setelah waktu mulai.';
        }

        return $errors;
    }

    private function storeUpload(string $field, string $directory, array $allowedMimes, int $maxMegabytes = 1): ?string
    {
        $file = $this->request->getFile($field);
        if ($file === null || ! $file->isValid()) {
            return null;
        }
        if ($file->getSizeByUnit('mb') > $maxMegabytes || ! in_array($file->getMimeType(), $allowedMimes, true)) {
            return null;
        }

        return $this->storeFile($file, $directory);
    }

    private function storeFile(UploadedFile $file, string $directory): string
    {
        $absoluteDirectory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $directory;
        if (! is_dir($absoluteDirectory) && ! mkdir($absoluteDirectory, 0775, true) && ! is_dir($absoluteDirectory)) {
            throw new \RuntimeException('Tidak dapat membuat direktori upload.');
        }

        $file->move($absoluteDirectory);

        return 'uploads/' . $directory . '/' . $file->getName();
    }
}
