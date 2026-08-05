<?php

namespace Tests\Unit;

use App\Http\Controllers\RuangLayanan\SkriningPTM\SkriningPTMController;
use App\Http\Requests\DiabetesRequest;
use App\Http\Requests\HipertensiRequest;
use App\Http\Requests\ObesitasRequest;
use App\Http\Requests\StoreKunjunganPTMRequest;
use App\Services\SkriningPTM\PelayananPTMService;
use App\Services\SkriningPTM\SkriningPTMService;
use App\Services\TindakanService;
use Illuminate\Http\RedirectResponse;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class SkriningPTMControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_tambah_kunjungan_ptm_meneruskan_data_tervalidasi_ke_service_dan_redirect_kembali(): void
    {
        $validated = [
            'idSkrining' => 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
            'idPelayanan' => 'b0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
            'nik_pasien' => '3273010101010001',
            'tanggal_skrining' => '2026-08-03',
            'id_petugas' => 'petugas-01',
            'fasyankes' => 'Puskesmas Banyuwangi',
            'jenis_kunjungan' => 'Kunjungan Baru',
            'keluhan_utama' => 'Pusing',
            'patient_id' => 'patient-01',
            'encounter_id' => 'encounter-01',
        ];

        $request = Mockery::mock(StoreKunjunganPTMRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validated);

        $pelayananService = Mockery::mock(PelayananPTMService::class);
        $pelayananService->shouldReceive('addKunjunganPTM')
            ->once()
            ->with($validated);

        $controller = new SkriningPTMController(
            Mockery::mock(SkriningPTMService::class),
            $pelayananService,
            Mockery::mock(TindakanService::class),
        );

        $response = $controller->tambahKunjunganPTM($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
    public function test_add_pemeriksaan_diabetes_meneruskan_data_tervalidasi_ke_service(): void
    {
        $validated = [
            'skriningId' => 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
            'gdp' => 95,
            'interpretasi_gdp' => 'Normal',
            'gds' => 120,
            'interpretasi_gds' => 'Normal',
            'hba1c' => 5.6,
            'interpretasi_hba1c' => 'Normal',
            'gd2pp' => 130,
            'interpretasi_gd2pp' => 'Normal',
        ];

        $request = Mockery::mock(DiabetesRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validated);

        $pelayananService = Mockery::mock(PelayananPTMService::class);
        $pelayananService->shouldReceive('saveDiabetes')->once()->with($validated);

        $response = $this->makeController($pelayananService)->addPemeriksaanDiabetes($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_add_pemeriksaan_hipertensi_meneruskan_data_tervalidasi_ke_service(): void
    {
        $validated = [
            'skriningId' => 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
            'sistolik' => 120,
            'diastolik' => 80,
            'kategori_hipertensi' => 'Normal',
            'suhu' => 36.5,
            'nadi' => 72,
            'pernapasan' => 18,
        ];

        $request = Mockery::mock(HipertensiRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validated);

        $pelayananService = Mockery::mock(PelayananPTMService::class);
        $pelayananService->shouldReceive('saveHipertensi')->once()->with($validated);

        $response = $this->makeController($pelayananService)->addPemeriksaanHipertensi($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_add_pemeriksaan_obesitas_meneruskan_data_tervalidasi_ke_service(): void
    {
        $validated = [
            'skriningId' => 'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
            'berat_badan' => 60,
            'tinggi_badan' => 165,
            'imt' => 22.04,
            'interpretasi_imt' => 'Normal',
            'lingkar_perut' => 80,
            'interpretasi_lp' => 'Normal',
        ];

        $request = Mockery::mock(ObesitasRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validated);

        $pelayananService = Mockery::mock(PelayananPTMService::class);
        $pelayananService->shouldReceive('saveObesitas')->once()->with($validated);

        $response = $this->makeController($pelayananService)->addPemeriksaanObesitas($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    private function makeController(PelayananPTMService $pelayananService): SkriningPTMController
    {
        return new SkriningPTMController(
            Mockery::mock(SkriningPTMService::class),
            $pelayananService,
            Mockery::mock(TindakanService::class),
        );
    }
}
