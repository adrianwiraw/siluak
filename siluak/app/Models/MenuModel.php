<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menus';
    protected $allowedFields = ['nama_layanan', 'slug', 'icon', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
