<?php

namespace App\Controllers;

use App\Models\MenuModel;

class MenuController extends BaseController
{
    public function index()
    {
        return view('admin/menus', ['menus' => (new MenuModel())->orderBy('nama_layanan')->findAll()]);
    }

    public function create()
    {
        return view('admin/menu_form');
    }

    public function store()
    {
        $name = trim((string) $this->request->getPost('nama_layanan'));
        if ($name === '') {
            return redirect()->back()->withInput()->with('error', 'Nama layanan wajib diisi.');
        }

        (new MenuModel())->insert([
            'nama_layanan' => $name,
            'slug' => url_title($name, '-', true),
            'icon' => trim((string) $this->request->getPost('icon')) ?: 'description',
            'is_active' => 1,
        ]);

        return redirect()->to(site_url('admin/layanan/index'))->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function toggle(int $id)
    {
        $model = new MenuModel();
        $menu = $model->find($id);
        if ($menu === null) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        $model->update($id, ['is_active' => (int) ! (bool) $menu['is_active']]);

        return redirect()->back()->with('success', 'Status menu berhasil diperbarui.');
    }
}
