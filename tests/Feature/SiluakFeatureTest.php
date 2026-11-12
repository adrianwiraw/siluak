<?php

namespace Tests\Feature;

use App\Models\AtkModel;
use App\Models\BarangAtkModel;
use App\Models\KerusakanAlatModel;
use App\Models\KerusakanGedungModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

final class SiluakFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    public function testPortalRendersWithMigratedSchema(): void
    {
        $this->get('/')->assertOK();
    }

    public function testAdminDashboardAndSearchRenderForAuthenticatedUser(): void
    {
        $this->withSession(['logged_in' => true])->get('/dashboard')->assertOK();
        $this->withSession(['logged_in' => true])->get('/search?q=admin')->assertOK();
    }

    public function testAdminLoginAcceptsExistingPasswordHash(): void
    {
        (new UserModel())->insert([
            'name' => 'Admin Test',
            'email' => 'admin@example.test',
            'password' => password_hash('correct-horse', PASSWORD_DEFAULT),
            'role' => 'umpeg',
        ]);

        $result = $this->post('/login/admin', [
            'email' => 'admin@example.test',
            'password' => 'correct-horse',
        ]);

        $result->assertRedirect();
        $this->assertSame('true', session()->get('logged_in') ? 'true' : 'false');
    }

    public function testPublicEquipmentReportIsPersisted(): void
    {
        $result = $this->post('/layanan/store/alat', [
            'nama' => 'Pemohon',
            'bidang' => 'Umum',
            'jenis_alat' => 'Komputer',
            'nama_alat' => 'PC-01',
            'kerusakan' => 'Tidak dapat menyala',
        ]);

        $result->assertRedirect();
        $record = (new KerusakanAlatModel())->first();
        $this->assertSame(1, (new KerusakanAlatModel())->countAllResults());
        $this->assertMatchesRegularExpression('/^SLK-ALAT-\d{8}-[A-F0-9]{16}$/', $record['ticket_code']);

        $ticketResult = $this->get('/tiket/' . $record['ticket_code']);
        $ticketResult->assertOK();
        $this->assertStringContainsString($record['ticket_code'], $ticketResult->response()->getBody());
        $this->assertStringContainsString('Status: Menunggu', $ticketResult->response()->getBody());
    }

    public function testTicketLookupRejectsUnknownCodesWithoutShowingRequestDetails(): void
    {
        $result = $this->get('/tiket/SLK-ALAT-20261112-0123456789ABCDEF');

        $result->assertRedirectTo(site_url('tiket'));
        $this->assertSame('Tiket tidak ditemukan. Periksa kembali kode yang dimasukkan.', session()->getFlashdata('error'));
    }

    public function testAdminEquipmentReportUsesOnlyExistingSchemaFields(): void
    {
        $result = $this->withSession(['logged_in' => true])->post('/laporan/peralatan/store', [
            'nama' => 'Admin',
            'bidang' => 'Umum',
            'nama_alat' => 'Printer',
            'jenis_alat' => 'Printer',
            'kerusakan' => 'Kertas tersangkut',
            'status' => 'Menunggu',
            'prioritas' => 'Tinggi',
        ]);

        $result->assertRedirect();
        $record = (new KerusakanAlatModel())->first();
        $this->assertSame('Tinggi', $record['prioritas']);
        $this->assertArrayNotHasKey('seksi', $record);
    }

    public function testAdminBuildingReportMapsDescriptionToPersistedColumn(): void
    {
        $result = $this->withSession(['logged_in' => true])->post('/laporan/gedung/store', [
            'nama' => 'Admin',
            'telepon' => '08123456789',
            'bidang' => 'Umum',
            'gedung' => 'Gedung A',
            'lokasi' => 'Lantai 1',
            'kerusakan' => 'Atap bocor',
            'prioritas' => 'Sedang',
        ]);

        $result->assertRedirect();
        $record = (new KerusakanGedungModel())->first();
        $this->assertSame('Atap bocor', $record['deskripsi']);
        $this->assertSame('Menunggu', $record['status']);
    }

    public function testAtkApprovalAndRejectionAdjustStockAtomically(): void
    {
        $stockId = (new BarangAtkModel())->insert([
            'nama_barang' => 'Kertas A4',
            'satuan' => 'rim',
            'stok' => 50,
            'kategori' => 'Kertas',
            'stok_minimal' => 10,
        ]);
        $requestId = (new AtkModel())->insert([
            'nama' => 'Pemohon',
            'nip' => '123456',
            'bidang' => 'Umum',
            'items' => json_encode([[
                'nama_barang' => 'Kertas A4',
                'jumlah' => 12,
                'satuan' => 'rim',
            ]], JSON_THROW_ON_ERROR),
            'diberi' => '0',
            'status' => 'Menunggu',
        ]);

        $this->withSession(['logged_in' => true])->post('/admin/update-status/atk/' . $requestId, [
            'status' => 'Disetujui',
            'items' => [['diberikan' => '12']],
        ]);
        $this->assertSame(38, (int) (new BarangAtkModel())->find($stockId)['stok']);

        $this->withSession(['logged_in' => true])->post('/admin/update-status/atk/' . $requestId, [
            'status' => 'Ditolak',
        ]);
        $this->assertSame(50, (int) (new BarangAtkModel())->find($stockId)['stok']);
    }

    public function testAtkApprovalRejectsCombinedItemsThatBreakMinimumStock(): void
    {
        $stockId = (new BarangAtkModel())->insert([
            'nama_barang' => 'Tinta',
            'satuan' => 'buah',
            'stok' => 50,
            'kategori' => 'Tinta',
            'stok_minimal' => 10,
        ]);
        $requestId = (new AtkModel())->insert([
            'nama' => 'Pemohon',
            'nip' => '123456',
            'bidang' => 'Umum',
            'items' => json_encode([
                ['nama_barang' => 'Tinta', 'jumlah' => 21, 'satuan' => 'buah'],
                ['nama_barang' => 'Tinta', 'jumlah' => 20, 'satuan' => 'buah'],
            ], JSON_THROW_ON_ERROR),
            'diberi' => '0',
            'status' => 'Menunggu',
        ]);

        $this->withSession(['logged_in' => true])->post('/admin/update-status/atk/' . $requestId, [
            'status' => 'Disetujui',
            'items' => [
                ['diberikan' => '21'],
                ['diberikan' => '20'],
            ],
        ]);

        $this->assertSame(50, (int) (new BarangAtkModel())->find($stockId)['stok']);
        $this->assertSame('Menunggu', (new AtkModel())->find($requestId)['status']);
    }

    public function testAdminCanDownloadXlsxExport(): void
    {
        $result = $this->withSession(['logged_in' => true])->get('/export/bmd');

        $result->assertOK();
        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $result->response()->getHeaderLine('Content-Type'),
        );
    }

    public function testAdminCanDownloadPdfExport(): void
    {
        $id = (new KerusakanAlatModel())->insert([
            'nama' => 'Pemohon',
            'bidang' => 'Umum',
            'jenis_alat' => 'Printer',
            'nama_alat' => 'Printer 1',
            'kerusakan' => 'Macet',
            'status' => 'Menunggu',
            'prioritas' => 'Sedang',
        ]);

        $result = $this->withSession(['logged_in' => true])->get('/laporan/peralatan/export-item/' . $id);

        $result->assertOK();
        $this->assertSame('application/pdf', $result->response()->getHeaderLine('Content-Type'));
    }

    public function testMediaEndpointOnlyServesStoredUploadFiles(): void
    {
        $uploadDirectory = WRITEPATH . 'uploads';
        if (! is_dir($uploadDirectory) && ! mkdir($uploadDirectory, 0775, true) && ! is_dir($uploadDirectory)) {
            $this->fail('Could not create temporary upload directory.');
        }

        $path = tempnam($uploadDirectory, 'test-');
        $this->assertNotFalse($path);
        file_put_contents($path, 'stored test file');

        try {
            $result = $this->get('/media/uploads/' . basename($path));
            $result->assertOK();
            $this->assertSame('stored test file', $result->response()->getBody());
        } finally {
            unlink($path);
        }
    }
}
