<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanKendaraanModel extends Model
{
    protected $table = 'peminjaman_kendaraans';
    protected $allowedFields = [
        'nama', 'nip', 'telepon', 'bidang', 'jenis_kendaraan', 'tujuan', 'kegiatan',
        'tgl_pinjam', 'tgl_kembali', 'status', 'surat_permohonan',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
