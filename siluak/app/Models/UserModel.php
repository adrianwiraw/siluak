<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'remember_token',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
}
