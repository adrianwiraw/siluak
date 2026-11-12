<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTicketCodesToRequests extends Migration
{
    private const TABLES = [
        'pengajuan_bmds',
        'kerusakan_alats',
        'kerusakan_gedungs',
        'peminjaman_kendaraans',
        'peminjaman_ruangans',
        'atk',
    ];

    public function up()
    {
        foreach (self::TABLES as $table) {
            if (! $this->forge->addColumn($table, [
                'ticket_code' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            ])) {
                throw new \RuntimeException('Gagal menambahkan kolom kode tiket pada tabel ' . $table . '.');
            }

            $this->forge->addUniqueKey('ticket_code', 'uq_' . $table . '_ticket_code');
            if (! $this->forge->processIndexes($table)) {
                throw new \RuntimeException('Gagal menambahkan indeks kode tiket pada tabel ' . $table . '.');
            }
        }
    }

    public function down()
    {
        foreach (self::TABLES as $table) {
            if (! $this->forge->dropKey($table, 'uq_' . $table . '_ticket_code', false)) {
                throw new \RuntimeException('Gagal menghapus indeks kode tiket pada tabel ' . $table . '.');
            }

            if (! $this->forge->dropColumn($table, 'ticket_code')) {
                throw new \RuntimeException('Gagal menghapus kolom kode tiket pada tabel ' . $table . '.');
            }
        }
    }
}
