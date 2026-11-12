<?php

namespace App\Controllers;

use App\Models\BarangAtkModel;

class StockController extends BaseController
{
    public function index()
    {
        $model = new BarangAtkModel();

        return view('admin/stock', [
            'items' => $model->orderBy('nama_barang')->findAll(),
            'totalTypes' => $model->countAllResults(),
            'lowStock' => $model->where('stok <= stok_minimal')->countAllResults(),
            'totalUnits' => $model->selectSum('stok')->first()['stok'] ?? 0,
        ]);
    }

    public function create()
    {
        return view('admin/stock_form', ['item' => null]);
    }

    public function detail(int $id)
    {
        $item = (new BarangAtkModel())->find($id);
        if ($item === null) {
            return redirect()->to(site_url('manajemen/stok'))->with('error', 'Barang tidak ditemukan.');
        }

        return view('admin/stock_form', ['item' => $item]);
    }

    public function store()
    {
        $data = $this->stockInput();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', 'Lengkapi nama barang, kategori, satuan, stok, dan stok minimal.');
        }

        $model = new BarangAtkModel();
        $existing = $model->where('nama_barang', $data['nama_barang'])->first();
        if ($existing !== null) {
            $model->update($existing['id'], [
                'stok' => $existing['stok'] + $data['stok'],
                'kategori' => $data['kategori'],
                'satuan' => $data['satuan'],
                'stok_minimal' => $data['stok_minimal'],
            ]);
        } else {
            $model->insert($data);
        }

        return redirect()->to(site_url('manajemen/stok'))->with('success', 'Stok berhasil diperbarui.');
    }

    public function update(int $id)
    {
        $data = $this->stockInput();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', 'Data barang tidak valid.');
        }
        if (! (new BarangAtkModel())->find($id)) {
            return redirect()->to(site_url('manajemen/stok'))->with('error', 'Barang tidak ditemukan.');
        }

        (new BarangAtkModel())->update($id, $data);

        return redirect()->to(site_url('manajemen/stok'))->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new BarangAtkModel();
        if (! $model->find($id)) {
            return redirect()->to(site_url('manajemen/stok'))->with('error', 'Barang tidak ditemukan.');
        }
        $model->delete($id);

        return redirect()->to(site_url('manajemen/stok'))->with('success', 'Barang berhasil dihapus.');
    }

    private function stockInput(): ?array
    {
        $data = [
            'nama_barang' => trim((string) $this->request->getPost('nama_barang')),
            'kategori' => trim((string) $this->request->getPost('kategori')),
            'satuan' => trim((string) $this->request->getPost('satuan')),
            'stok' => filter_var($this->request->getPost('stok'), FILTER_VALIDATE_INT),
            'stok_minimal' => filter_var($this->request->getPost('stok_minimal'), FILTER_VALIDATE_INT),
        ];

        if ($data['nama_barang'] === '' || $data['kategori'] === '' || $data['satuan'] === ''
            || $data['stok'] === false || $data['stok'] < 0
            || $data['stok_minimal'] === false || $data['stok_minimal'] < 0) {
            return null;
        }

        return $data;
    }
}
