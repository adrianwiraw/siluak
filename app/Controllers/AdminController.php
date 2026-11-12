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
use App\Models\UserModel;

class AdminController extends BaseController
{
    private const SEARCH_COLUMNS = [
        'bmd' => ['nama', 'email', 'kode', 'program', 'kegiatan', 'output', 'keterangan'],
        'peralatan' => ['nama', 'jenis_alat', 'nama_alat', 'kerusakan'],
        'gedung' => ['nama', 'gedung', 'lokasi', 'deskripsi'],
        'kendaraan' => ['nama', 'nip', 'jenis_kendaraan', 'tujuan', 'kegiatan'],
        'ruangan' => ['nama', 'nip', 'ruangan', 'acara'],
        'atk' => ['nama', 'nip', 'bidang'],
    ];

    private const RECORDS = [
        'bmd' => ['model' => PengajuanBmdModel::class, 'title' => 'Pengajuan BMD'],
        'peralatan' => ['model' => KerusakanAlatModel::class, 'title' => 'Kerusakan Peralatan'],
        'gedung' => ['model' => KerusakanGedungModel::class, 'title' => 'Kerusakan Gedung'],
        'kendaraan' => ['model' => PeminjamanKendaraanModel::class, 'title' => 'Peminjaman Kendaraan'],
        'ruangan' => ['model' => PeminjamanRuanganModel::class, 'title' => 'Peminjaman Ruangan'],
        'atk' => ['model' => AtkModel::class, 'title' => 'Permintaan ATK'],
    ];

    public function dashboard()
    {
        $pending = 0;
        $completed = 0;
        $total = 0;
        $weekCurrent = 0;
        $weekLast = 0;
        $weekStart = new \DateTimeImmutable('monday this week 00:00:00');
        $lastWeekStart = $weekStart->modify('-7 days');
        $chartData = array_fill(0, 7, 0);
        $pendingRequests = [];
        $activities = [];
        $activityAppearance = [
            'bmd' => ['icon' => 'inventory_2', 'color' => 'blue'],
            'peralatan' => ['icon' => 'build', 'color' => 'orange'],
            'gedung' => ['icon' => 'domain', 'color' => 'red'],
            'kendaraan' => ['icon' => 'directions_car', 'color' => 'purple'],
            'ruangan' => ['icon' => 'meeting_room', 'color' => 'indigo'],
            'atk' => ['icon' => 'school', 'color' => 'emerald'],
        ];

        foreach (self::RECORDS as $type => $record) {
            $modelClass = $record['model'];
            $model = new $modelClass();
            $pending += $model->where('status', 'Menunggu')->countAllResults();
            $completed += $model->where('status', 'Selesai')->countAllResults();
            $total += $model->countAllResults();

            foreach ($model->where('status', 'Menunggu')->orderBy('created_at', 'DESC')->findAll(10) as $item) {
                $pendingRequests[] = [
                    'type' => $type,
                    'title' => $record['title'],
                    'id' => $item['id'],
                    'name' => $item['nama'] ?? '-',
                    'created_at' => $item['created_at'] ?? '',
                    'status' => $item['status'] ?? 'Menunggu',
                ];
            }

            foreach ($model->orderBy('created_at', 'DESC')->findAll(5) as $item) {
                $activities[] = [
                    'type' => $record['title'],
                    'name' => $item['nama'] ?? '-',
                    'created_at' => $item['created_at'] ?? '',
                    'icon' => $activityAppearance[$type]['icon'],
                    'color' => $activityAppearance[$type]['color'],
                ];
            }

            $weekCurrent += $model->where('created_at >=', $weekStart->format('Y-m-d H:i:s'))->countAllResults();
            $weekLast += $model->where('created_at >=', $lastWeekStart->format('Y-m-d H:i:s'))
                ->where('created_at <', $weekStart->format('Y-m-d H:i:s'))
                ->countAllResults();

            for ($day = 0; $day < 7; $day++) {
                $date = $weekStart->modify('+' . $day . ' days');
                $chartData[$day] += $model->where('created_at >=', $date->format('Y-m-d 00:00:00'))
                    ->where('created_at <', $date->modify('+1 day')->format('Y-m-d 00:00:00'))
                    ->countAllResults();
            }
        }

        usort($pendingRequests, static fn (array $left, array $right): int => strcmp($right['created_at'], $left['created_at']));
        usort($activities, static fn (array $left, array $right): int => strcmp($right['created_at'], $left['created_at']));

        return view('admin/dashboard', [
            'pending' => $pending,
            'completed' => $completed,
            'total' => $total,
            'pendingRequests' => array_slice($pendingRequests, 0, 10),
            'weekCurrent' => $weekCurrent,
            'weekLast' => $weekLast,
            'weeklyChange' => $weekLast > 0
                ? (int) round((($weekCurrent - $weekLast) / $weekLast) * 100)
                : ($weekCurrent > 0 ? 100 : 0),
            'chartData' => $chartData,
            'records' => self::RECORDS,
            'activities' => array_slice($activities, 0, 5),
        ]);
    }

    public function listing(string $type)
    {
        if (! isset(self::RECORDS[$type])) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Jenis laporan tidak dikenal.');
        }

        $modelClass = self::RECORDS[$type]['model'];
        $model = new $modelClass();

        return view('admin/records', [
            'type' => $type,
            'title' => self::RECORDS[$type]['title'],
            'items' => $model->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function detail(string $type, int $id)
    {
        if (! isset(self::RECORDS[$type])) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Jenis laporan tidak dikenal.');
        }

        $modelClass = self::RECORDS[$type]['model'];
        $item = (new $modelClass())->find($id);
        if ($item === null) {
            return redirect()->back()->with('error', 'Data laporan tidak ditemukan.');
        }

        return view('admin/detail', [
            'type' => $type,
            'title' => self::RECORDS[$type]['title'],
            'item' => $item,
        ]);
    }

    public function updateStatus(string $type, int $id)
    {
        if (! isset(self::RECORDS[$type])) {
            return redirect()->back()->with('error', 'Tipe layanan tidak dikenali.');
        }

        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['Menunggu', 'Disetujui', 'Proses', 'Selesai', 'Ditolak'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $db = db_connect();
        $db->transBegin();

        $modelClass = self::RECORDS[$type]['model'];
        $model = new $modelClass();
        $item = $model->find($id);
        if ($item === null) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Data laporan tidak ditemukan.');
        }

        $changes = ['status' => $status];
        if (in_array($type, ['peralatan', 'gedung'], true)) {
            $priority = (string) $this->request->getPost('prioritas');
            if ($priority !== '') {
                $changes['prioritas'] = $priority;
            }
        }

        if ($type === 'kendaraan' && $this->request->getPost('plat_nomor')) {
            $changes['jenis_kendaraan'] = $item['jenis_kendaraan'] . ' (' . trim($this->request->getPost('plat_nomor')) . ')';
        }

        if ($type === 'atk') {
            $stockResult = $this->applyAtkStockChange($item, $status);
            if (isset($stockResult['message'])) {
                $db->transRollback();
                return redirect()->back()->with('error', $stockResult['message']);
            }
            if (isset($stockResult['items'])) {
                $changes['items'] = json_encode($stockResult['items'], JSON_THROW_ON_ERROR);
            }
        }

        $model->update($id, $changes);
        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Perubahan tidak berhasil disimpan.');
        }
        $db->transCommit();

        return redirect()->back()->with('success', 'Status permohonan berhasil diperbarui.');
    }

    public function search()
    {
        $term = trim((string) $this->request->getGet('q'));
        $results = [];
        if ($term !== '') {
            foreach (self::RECORDS as $type => $record) {
                $modelClass = $record['model'];
                $builder = (new $modelClass())->builder();
                $searchable = self::SEARCH_COLUMNS[$type];
                $builder->groupStart();
                foreach ($searchable as $index => $column) {
                    $index === 0 ? $builder->like($column, $term) : $builder->orLike($column, $term);
                }
                $rows = $builder->groupEnd()->get()->getResultArray();
                foreach ($rows as $row) {
                    $results[] = ['type' => $type, 'title' => $record['title'], 'row' => $row];
                }
            }

            $userBuilder = (new UserModel())->builder();
            $users = $userBuilder->groupStart()
                ->like('name', $term)
                ->orLike('email', $term)
                ->groupEnd()
                ->get()
                ->getResultArray();
            foreach ($users as $user) {
                unset($user['password'], $user['remember_token']);
                $results[] = ['type' => 'users', 'title' => 'Pengguna', 'row' => $user];
            }
        }

        return view('admin/search', ['query' => $term, 'results' => $results]);
    }

    private function applyAtkStockChange(array $request, string $nextStatus): ?array
    {
        $items = json_decode((string) $request['items'], true);
        if (! is_array($items)) {
            $items = [];
        }

        $wasApproved = in_array($request['status'], ['Disetujui', 'Selesai'], true);
        $mustReturnStock = $wasApproved && in_array($nextStatus, ['Ditolak', 'Menunggu'], true);
        $submitted = $this->request->getPost('items');
        $stockModel = new BarangAtkModel();

        if ($nextStatus === 'Disetujui' && is_array($submitted)) {
            $planned = [];
            $totals = [];
            foreach ($items as $index => $item) {
                if (! isset($submitted[$index]['diberikan'])) {
                    continue;
                }
                if (! isset($item['nama_barang'])) {
                    return ['message' => 'Detail barang pada permohonan tidak valid.'];
                }
                $quantity = filter_var($submitted[$index]['diberikan'], FILTER_VALIDATE_INT);
                if ($quantity === false || $quantity < 0) {
                    return ['message' => 'Jumlah yang diberikan harus berupa angka nol atau lebih.'];
                }

                $stock = $stockModel->where('nama_barang', trim((string) $item['nama_barang']))->first();
                if ($stock === null) {
                    return ['message' => 'Barang [' . $item['nama_barang'] . '] tidak ditemukan di gudang.'];
                }
                $planned[] = ['index' => $index, 'quantity' => $quantity, 'stock' => $stock];
                $totals[$stock['id']] = ($totals[$stock['id']] ?? 0) + $quantity;
            }

            foreach ($planned as $change) {
                $stockId = $change['stock']['id'];
                $availableAfter = $change['stock']['stok'] - ($wasApproved ? 0 : $totals[$stockId]);
                if ($availableAfter < $change['stock']['stok_minimal']) {
                    return [
                        'message' => 'Stok [' . $change['stock']['nama_barang'] . '] tidak cukup untuk melewati batas minimum.',
                    ];
                }
            }

            if (! $wasApproved) {
                $updatedStock = [];
                foreach ($planned as $change) {
                    $stockId = $change['stock']['id'];
                    if (isset($updatedStock[$stockId])) {
                        continue;
                    }
                    $stockModel->update($stockId, [
                        'stok' => $change['stock']['stok'] - $totals[$stockId],
                    ]);
                    $updatedStock[$stockId] = true;
                }
            }

            foreach ($planned as $change) {
                $items[$change['index']]['diberikan'] = $change['quantity'];
            }
        }

        if ($mustReturnStock) {
            foreach ($items as $item) {
                $stock = $stockModel->where('nama_barang', trim((string) $item['nama_barang']))->first();
                if ($stock !== null) {
                    $stockModel->update($stock['id'], [
                        'stok' => $stock['stok'] + (int) ($item['diberikan'] ?? 0),
                    ]);
                }
            }
        }

        return ['items' => $items];
    }
}
