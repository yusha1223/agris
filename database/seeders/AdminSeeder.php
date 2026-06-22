<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $provinsiId = '35';
        $kabupatenId = '35.09';
        $kecamatanId = '35.09.20';
        $desaId = '35.09.20.1004';

        Provinsi::updateOrCreate(['id' => $provinsiId], ['namaProvinsi' => 'JAWA TIMUR']);
        Kabupaten::updateOrCreate(['id' => $kabupatenId], ['provinsiId' => $provinsiId, 'namaKabupaten' => 'JEMBER']);

        $kecamatan = Kecamatan::updateOrCreate(['id' => $kecamatanId], [
            'kabupatenId' => $kabupatenId,
            'namaKecamatan' => 'PATRANG',
        ]);

        Desa::updateOrCreate(['id' => $desaId], [
            'kecamatanId' => $kecamatanId,
            'namaDesa' => 'SLAWU',
        ]);

        $biteshipAreaId = $this->getBiteshipAreaId('PATRANG');
        if ($biteshipAreaId) {
            \Illuminate\Support\Facades\DB::table('biteship_areas')->updateOrInsert(
                ['desaId' => $desaId],
                [
                    'biteship_area_id' => $biteshipAreaId,
                    'biteship_name' => 'PATRANG, JEMBER',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        User::create([
            'namaLengkap' => 'Admin Utama',
            'email'       => 'agrisagroindustri@gmail.com',
            'password'    => Hash::make('admin123'),
            'noTelp'      => '08123456789',
            'isActive'    => true,
            'isAdmin'     => true,
            'detailAlamat'=> 'Jl. Manyar Gg. Kelapa, Puring',
            'desaId'      => $desaId,
        ]);
    }

    private function getBiteshipAreaId($namaKecamatan)
    {
        $baseUrl = config('services.biteship.url', 'https://api-sandbox.biteship.com/v1');
        $apiKey = config('services.biteship.key');

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/maps/areas", [
                    'countries' => 'ID',
                    'input' => $namaKecamatan,
                    'type' => 'single'
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (!empty($areas)) {
                    return $areas[0]['id'];
                }
            }
        } catch (\Exception $e) {
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/areas", [
                    'country' => 'ID',
                    'type' => 'district',
                    'query' => $namaKecamatan
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                foreach ($areas as $area) {
                    if (strtoupper($area['name'] ?? '') === strtoupper($namaKecamatan)) {
                        return $area['id'];
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return 'IDNP6IDNC148IDND843IDZ12250';
    }
}
