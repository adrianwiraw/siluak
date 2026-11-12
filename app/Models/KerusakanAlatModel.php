<?php

namespace App\Models;

use CodeIgniter\Model;

class KerusakanAlatModel extends Model
{
    protected $table = 'kerusakan_alats';
    protected $allowedFields = [
        'nama', 'bidang', 'jenis_alat', 'nama_alat', 'kerusakan', 'status', 'prioritas',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
