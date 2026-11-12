<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangAtkModel extends Model
{
    protected $table = 'barang_atks';
    protected $allowedFields = ['nama_barang', 'satuan', 'stok', 'kategori', 'stok_minimal'];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
