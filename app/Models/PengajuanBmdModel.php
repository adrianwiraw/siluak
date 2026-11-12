<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanBmdModel extends Model
{
    protected $table = 'pengajuan_bmds';
    protected $allowedFields = [
        'nama', 'email', 'bidang', 'kode', 'program', 'kegiatan', 'output',
        'keterangan', 'items', 'status', 'ticket_code',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
