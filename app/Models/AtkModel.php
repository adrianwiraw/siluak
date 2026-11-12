<?php

namespace App\Models;

use CodeIgniter\Model;

class AtkModel extends Model
{
    protected $table = 'atk';
    protected $allowedFields = ['nama', 'nip', 'bidang', 'items', 'diberi', 'foto', 'status'];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
