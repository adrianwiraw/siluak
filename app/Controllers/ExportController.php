<?php

namespace App\Controllers;

use App\Models\AtkModel;
use App\Models\BarangAtkModel;
use App\Models\KerusakanAlatModel;
use App\Models\KerusakanGedungModel;
use App\Models\PengajuanBmdModel;
use App\Models\PeminjamanKendaraanModel;
use App\Models\PeminjamanRuanganModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends BaseController
{
    private const EXPORTS = [
        'bmd' => ['model' => PengajuanBmdModel::class, 'filename' => 'daftar_kebutuhan_bmd.xlsx'],
        'peralatan' => ['model' => KerusakanAlatModel::class, 'filename' => 'laporan_peralatan.xlsx'],
        'gedung' => ['model' => KerusakanGedungModel::class, 'filename' => 'laporan_gedung.xlsx'],
        'kendaraan' => ['model' => PeminjamanKendaraanModel::class, 'filename' => 'peminjaman_kendaraan.xlsx'],
        'ruangan' => ['model' => PeminjamanRuanganModel::class, 'filename' => 'peminjaman_ruangan.xlsx'],
        'atk' => ['model' => AtkModel::class, 'filename' => 'permintaan_atk.xlsx'],
        'stok' => ['model' => BarangAtkModel::class, 'filename' => 'manajemen_stok.xlsx'],
    ];

    public function download(string $type)
    {
        if (! isset(self::EXPORTS[$type])) {
            return redirect()->back()->with('error', 'Jenis ekspor tidak dikenal.');
        }

        $modelClass = self::EXPORTS[$type]['model'];
        $records = (new $modelClass())->orderBy('id', 'ASC')->findAll();
        $rows = $this->flatten($records, $type);
        $filename = $type === 'atk'
            ? 'permintaan_atk_' . date('Y-m-d') . '.xlsx'
            : self::EXPORTS[$type]['filename'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if ($rows === []) {
            $sheet->fromArray([['Tidak ada data']], null, 'A1');
        } else {
            $sheet->fromArray([array_keys($rows[0])], null, 'A1');
            $sheet->fromArray(array_map('array_values', $rows), null, 'A2');
            $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
            for ($columnIndex = 1; $columnIndex <= $lastColumn; $columnIndex++) {
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setAutoSize(true);
            }
        }

        $temporaryFile = tempnam(sys_get_temp_dir(), 'siluak-export-');
        if ($temporaryFile === false) {
            throw new \RuntimeException('Tidak dapat menyiapkan file ekspor.');
        }

        try {
            (new Xlsx($spreadsheet))->save($temporaryFile);
            $content = file_get_contents($temporaryFile);
            if ($content === false) {
                throw new \RuntimeException('Tidak dapat membaca file hasil ekspor.');
            }
        } finally {
            @unlink($temporaryFile);
            $spreadsheet->disconnectWorksheets();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($content);
    }

    public function pdf(string $type, int $id)
    {
        if (! isset(self::EXPORTS[$type]) || $type === 'stok') {
            return redirect()->back()->with('error', 'Jenis dokumen tidak dikenal.');
        }

        $modelClass = self::EXPORTS[$type]['model'];
        $record = (new $modelClass())->find($id);
        if ($record === null) {
            return redirect()->back()->with('error', 'Data laporan tidak ditemukan.');
        }

        $html = '<h1>' . esc(self::EXPORTS[$type]['filename']) . '</h1><table border="1" cellpadding="6" cellspacing="0"><tbody>';
        foreach ($record as $field => $value) {
            $value = is_scalar($value) || $value === null ? (string) $value : json_encode($value);
            $html .= '<tr><th>' . esc(ucwords(str_replace('_', ' ', $field))) . '</th><td>' . esc($value) . '</td></tr>';
        }
        $html .= '</tbody></table>';

        $pdf = new Dompdf();
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan-' . $type . '-' . $id . '.pdf"')
            ->setBody($pdf->output());
    }

    private function flatten(array $records, string $type): array
    {
        $rows = [];
        foreach ($records as $record) {
            if (isset($record['items'])) {
                $items = is_array($record['items']) ? $record['items'] : json_decode((string) $record['items'], true);
                if (is_array($items) && $items !== []) {
                    foreach ($items as $item) {
                        $rows[] = array_merge(
                            array_diff_key($record, ['items' => true]),
                            is_array($item) ? $item : ['item' => $item]
                        );
                    }
                    continue;
                }
            }
            $rows[] = $record;
        }

        if ($type === 'atk') {
            return array_map(static function (array $row): array {
                return array_intersect_key($row, array_flip([
                    'id', 'created_at', 'nama', 'bidang', 'nama_barang', 'jumlah', 'diberikan',
                    'satuan', 'status', 'foto',
                ]));
            }, $rows);
        }

        return $rows;
    }
}
