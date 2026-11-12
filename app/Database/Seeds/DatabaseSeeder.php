<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $email = trim((string) env('SILUAK_ADMIN_EMAIL'));
        $password = (string) env('SILUAK_ADMIN_PASSWORD');

        if ($email === '' || $password === '') {
            return;
        }

        $users = $this->db->table('users');
        if ($users->where('email', $email)->countAllResults() > 0) {
            return;
        }

        $users->insert([
            'name' => 'Admin UMPEG',
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'umpeg',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
