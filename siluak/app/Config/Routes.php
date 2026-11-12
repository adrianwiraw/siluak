<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'PortalController::index', ['as' => 'welcome']);
$routes->get('login/admin', 'AuthController::showLogin', ['as' => 'login.admin']);
$routes->post('login/admin', 'AuthController::login', ['as' => 'login.submit']);
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth', 'as' => 'logout']);

$routes->get('layanan', 'PortalController::index', ['as' => 'layanan.index']);
$routes->post('layanan/store/(:segment)', 'SubmissionController::store/$1', ['as' => 'layanan.store']);
$routes->get('layanan/detail/(:num)', 'PortalController::detail/$1', ['filter' => 'auth', 'as' => 'layanan.detail']);
$routes->get('media/(:any)', 'FileController::show/$1');

$routes->group('umpeg/laporan', static function (RouteCollection $routes): void {
    foreach (['bmd', 'kerusakan_alat', 'kerusakan_gedung', 'pinjam_kendaraan', 'pinjam_ruangan', 'atk'] as $type) {
        $routes->get($type, 'SubmissionController::listing/' . $type);
        $routes->post($type . '/store', 'SubmissionController::store/' . $type);
    }

    $routes->get('pinjam_kendaraan/download/(:num)', 'SubmissionController::downloadVehicleTemplate/$1');
    $routes->post('pinjam_kendaraan/upload/(:num)', 'SubmissionController::uploadVehicleLetter/$1');
});

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
    $routes->get('search', 'AdminController::search', ['as' => 'admin.search']);
    $routes->get('manajemen-stok', 'StockController::index');
    $routes->post('admin/update-status/(:segment)/(:num)', 'AdminController::updateStatus/$1/$2');
    $routes->get('export/(:segment)', 'ExportController::download/$1');

    $routes->get('bmd', 'AdminController::listing/bmd');
    $routes->get('bmd/detail/(:num)', 'AdminController::detail/bmd/$1');
    $routes->post('bmd/update/(:num)', 'AdminController::updateStatus/bmd/$1');
    $routes->get('bmd/export-excel', 'ExportController::download/bmd');
    $routes->post('bmd/export-excel', 'ExportController::download/bmd');
    $routes->get('bmd/export-item/(:num)', 'ExportController::pdf/bmd/$1');

    $routes->get('laporan/peralatan', 'AdminController::listing/peralatan');
    $routes->post('laporan/peralatan/store', 'SubmissionController::adminStoreEquipment');
    $routes->get('laporan/peralatan/detail/(:num)', 'AdminController::detail/peralatan/$1');
    $routes->post('laporan/peralatan/update/(:num)', 'AdminController::updateStatus/peralatan/$1');
    $routes->get('laporan/peralatan/export-excel', 'ExportController::download/peralatan');
    $routes->post('laporan/peralatan/export-excel', 'ExportController::download/peralatan');
    $routes->get('laporan/peralatan/export-item/(:num)', 'ExportController::pdf/peralatan/$1');

    $routes->get('laporan/gedung', 'AdminController::listing/gedung');
    $routes->post('laporan/gedung/store', 'SubmissionController::adminStoreBuilding');
    $routes->get('laporan/gedung/detail/(:num)', 'AdminController::detail/gedung/$1');
    $routes->post('laporan/gedung/update/(:num)', 'AdminController::updateStatus/gedung/$1');
    $routes->get('laporan/gedung/export-excel', 'ExportController::download/gedung');
    $routes->post('laporan/gedung/export-excel', 'ExportController::download/gedung');
    $routes->get('laporan/gedung/export-item/(:num)', 'ExportController::pdf/gedung/$1');

    $routes->get('peminjaman/kendaraan', 'AdminController::listing/kendaraan');
    $routes->get('peminjaman/kendaraan/detail/(:num)', 'AdminController::detail/kendaraan/$1');
    $routes->get('peminjaman/kendaraan/export-kendaraan', 'ExportController::download/kendaraan');
    $routes->get('peminjaman/ruang', 'AdminController::listing/ruangan');
    $routes->get('peminjaman/ruang/detail/(:num)', 'AdminController::detail/ruangan/$1');
    $routes->get('peminjaman/ruang/export-ruang', 'ExportController::download/ruangan');

    $routes->get('atk', 'AdminController::listing/atk');
    $routes->get('atk/detail', 'AdminController::listing/atk');
    $routes->get('atk/detail/(:num)', 'AdminController::detail/atk/$1');
    $routes->get('atk/export-excel', 'ExportController::download/atk');
    $routes->post('atk/export-excel', 'ExportController::download/atk');
    $routes->post('atk/atk/export-excel', 'ExportController::download/atk');

    $routes->get('manajemen/stok', 'StockController::index');
    $routes->get('manajemen/detail/(:num)', 'StockController::detail/$1');
    $routes->get('manajemen/create', 'StockController::create');
    $routes->post('manajemen/store', 'StockController::store');
    $routes->post('manajemen/update/(:num)', 'StockController::update/$1');
    $routes->delete('manajemen/destroy/(:num)', 'StockController::delete/$1');
    $routes->get('manajemen/export-excel', 'ExportController::download/stok');
    $routes->post('manajemen/export-excel', 'ExportController::download/stok');

    $routes->get('admin/layanan/index', 'MenuController::index');
    $routes->get('admin/layanan/add', 'MenuController::create');
    $routes->post('admin/layanan/store', 'MenuController::store');
    $routes->post('admin/layanan/toggle/(:num)', 'MenuController::toggle/$1');
});
