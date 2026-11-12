<?php

namespace App\Models;

use CodeIgniter\Model;

class KerusakanGedungModel extends Model
{
    protected $table = 'kerusakan_gedungs';
    protected $allowedFields = [
        'nama', 'telepon', 'bidang', 'gedung', 'lokasi', 'deskripsi', 'foto', 'status', 'prioritas', 'ticket_code',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
