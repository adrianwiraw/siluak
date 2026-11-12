<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanRuanganModel extends Model
{
    protected $table = 'peminjaman_ruangans';
    protected $allowedFields = [
        'nama', 'nip', 'telepon', 'bidang', 'ruangan', 'acara', 'tanggal',
        'waktu_mulai', 'waktu_selesai', 'status', 'ticket_code',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
