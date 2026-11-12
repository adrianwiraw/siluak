<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiluakTables extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id'                => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'email_verified_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'password'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'remember_token'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ], ['email']);

        $this->createTable('pengajuan_bmds', [
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'program'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'kegiatan'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'output'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'keterangan' => ['type' => 'TEXT'],
            'items'      => ['type' => 'JSON'],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
        ]);

        $this->createTable('kerusakan_alats', [
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'jenis_alat' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_alat'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'kerusakan'  => ['type' => 'TEXT'],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
            'prioritas'  => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Belum Diatur'],
        ]);

        $this->createTable('kerusakan_gedungs', [
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'telepon'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'gedung'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'lokasi'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'deskripsi'  => ['type' => 'TEXT'],
            'foto'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
            'prioritas'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);

        $this->createTable('peminjaman_kendaraans', [
            'id'                => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'nip'               => ['type' => 'VARCHAR', 'constraint' => 255],
            'telepon'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'jenis_kendaraan'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'tujuan'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'kegiatan'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'tgl_pinjam'        => ['type' => 'DATE'],
            'tgl_kembali'       => ['type' => 'DATE'],
            'status'            => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
            'surat_permohonan'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);

        $this->createTable('peminjaman_ruangans', [
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'nip'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'telepon'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'ruangan'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'acara'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal'        => ['type' => 'DATE'],
            'waktu_mulai'    => ['type' => 'TIME'],
            'waktu_selesai'  => ['type' => 'TIME'],
            'status'         => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
        ]);

        $this->createTable('menus', [
            'id'           => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama_layanan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon'         => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'description'],
            'is_active'    => ['type' => 'BOOLEAN', 'default' => true],
        ]);

        $this->createTable('atk', [
            'id'      => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'nip'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'bidang'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'items'   => ['type' => 'JSON'],
            'diberi'  => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '0'],
            'foto'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'  => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Menunggu'],
        ]);

        $this->createTable('barang_atks', [
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama_barang'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'satuan'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'stok'          => ['type' => 'INT'],
            'kategori'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'stok_minimal'  => ['type' => 'INT', 'default' => 10],
        ]);
    }

    public function down()
    {
        foreach ([
            'barang_atks',
            'atk',
            'menus',
            'peminjaman_ruangans',
            'peminjaman_kendaraans',
            'kerusakan_gedungs',
            'kerusakan_alats',
            'pengajuan_bmds',
            'users',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    private function createTable(string $name, array $fields, array $uniqueFields = []): void
    {
        $fields['created_at'] = ['type' => 'TIMESTAMP', 'null' => true];
        $fields['updated_at'] = ['type' => 'TIMESTAMP', 'null' => true];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');

        if ($uniqueFields !== []) {
            $this->forge->addUniqueKey($uniqueFields);
        }

        $this->forge->createTable($name, true);
    }
}
