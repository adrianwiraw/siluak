<?php

namespace App\Controllers;

use App\Models\AtkModel;
use App\Models\KerusakanAlatModel;
use App\Models\KerusakanGedungModel;
use App\Models\PengajuanBmdModel;
use App\Models\PeminjamanKendaraanModel;
use App\Models\PeminjamanRuanganModel;

class TicketController extends BaseController
{
    private const TYPES = [
        'BMD' => ['type' => 'bmd', 'title' => 'Usulan Rencana Kebutuhan BMD', 'model' => PengajuanBmdModel::class],
        'ALAT' => ['type' => 'alat', 'title' => 'Pelaporan Kerusakan Peralatan Kantor', 'model' => KerusakanAlatModel::class],
        'GEDUNG' => ['type' => 'gedung', 'title' => 'Pelaporan Kerusakan Gedung dan Bangunan', 'model' => KerusakanGedungModel::class],
        'KENDARAAN' => ['type' => 'kendaraan', 'title' => 'Peminjaman Kendaraan Dinas', 'model' => PeminjamanKendaraanModel::class],
        'RUANGAN' => ['type' => 'ruangan', 'title' => 'Peminjaman Ruangan Rapat', 'model' => PeminjamanRuanganModel::class],
        'ATK' => ['type' => 'atk', 'title' => 'Permohonan ATK', 'model' => AtkModel::class],
    ];

    public function index()
    {
        return view('portal/ticket_lookup');
    }

    public function lookup()
    {
        $code = strtoupper(trim((string) $this->request->getPost('kode')));
        if (! $this->isValidCode($code)) {
            return redirect()->to(site_url('tiket'))->withInput()->with('error', 'Format kode tiket tidak valid.');
        }

        return redirect()->to(site_url('tiket/' . $code));
    }

    public function show(string $code)
    {
        $code = strtoupper($code);
        if (! $this->isValidCode($code)) {
            return redirect()->to(site_url('tiket'))->with('error', 'Format kode tiket tidak valid.');
        }

        $prefix = explode('-', $code)[1];
        $definition = self::TYPES[$prefix];
        $modelClass = $definition['model'];
        $record = (new $modelClass())->where('ticket_code', $code)->first();
        if ($record === null) {
            return redirect()->to(site_url('tiket'))->with('error', 'Tiket tidak ditemukan. Periksa kembali kode yang dimasukkan.');
        }

        return view('portal/ticket_status', [
            'code' => $code,
            'title' => $definition['title'],
            'type' => $definition['type'],
            'status' => (string) ($record['status'] ?? 'Menunggu'),
            'createdAt' => (string) ($record['created_at'] ?? ''),
        ]);
    }

    private function isValidCode(string $code): bool
    {
        return preg_match('/^SLK-(BMD|ALAT|GEDUNG|KENDARAAN|RUANGAN|ATK)-\d{8}-[A-F0-9]{16}$/', $code) === 1;
    }
}
