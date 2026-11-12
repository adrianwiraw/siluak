<?php

namespace App\Controllers;

use App\Models\AtkModel;
use App\Models\BarangAtkModel;
use App\Models\KerusakanAlatModel;
use App\Models\KerusakanGedungModel;
use App\Models\MenuModel;
use App\Models\PengajuanBmdModel;
use App\Models\PeminjamanKendaraanModel;
use App\Models\PeminjamanRuanganModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PortalController extends BaseController
{
    public function index()
    {
        $menus = (new MenuModel())->where('is_active', 1)->orderBy('nama_layanan')->findAll();

        return view('portal/home', [
            'menus' => $menus,
            'stockItems' => (new BarangAtkModel())->where('stok >', 0)->orderBy('nama_barang')->findAll(),
            'approvedRooms' => (new PeminjamanRuanganModel())
                ->where('status', 'Disetujui')
                ->orderBy('created_at', 'DESC')
                ->findAll(6),
        ]);
    }

    public function detail(int $id)
    {
        $type = (string) $this->request->getGet('type');
        $models = $this->models();
        if (! isset($models[$type])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $modelClass = $models[$type];
        $record = (new $modelClass())->find($id);
        if ($record === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('portal/detail', ['type' => $type, 'record' => $record]);
    }

    private function models(): array
    {
        return [
            'bmd' => PengajuanBmdModel::class,
            'alat' => KerusakanAlatModel::class,
            'gedung' => KerusakanGedungModel::class,
            'kendaraan' => PeminjamanKendaraanModel::class,
            'ruangan' => PeminjamanRuanganModel::class,
            'atk' => AtkModel::class,
        ];
    }
}
