<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class c_profile extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function show()
    {
        $user = Auth::user();

        if ($user->desaId && !$user->biteship_location_id) {
            $this->syncBiteshipLocation($user);
            $user->refresh();
        }

        $view = $user->isAdmin ? 'admin.profile' : 'agen.profile';
        return view($view, compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'noTelp'           => 'required|numeric|digits_between:4,15|unique:users,noTelp,' . $user->id,
            'namaLengkap'      => $user->isAdmin ? 'nullable|string|max:255' : 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'detailAlamat'     => 'nullable|string',
            'fotoProfil'       => 'nullable|image|mimes:jpeg,png,jpg|max:10048',
            'current_password' => 'required_with:password',
            'password'         => 'nullable|min:8',
            'desaId'           => 'nullable',
        ], [
            'required'                       => 'Data wajib diisi!',
            'noTelp.numeric'                 => 'Nomor telepon harus berupa angka.',
            'noTelp.unique'                  => 'Nomor Telpon sudah digunakan.',
            'noTelp.digits_between'          => 'Nomor telepon harus antara 4 sampai 15 digit.',
            'password.min'                   => 'Password baru minimal 8 karakter.',
            'current_password.required_with' => 'Konfirmasi password lama wajib diisi.',
            'fotoProfil.image'               => 'File harus berupa gambar.',
            'fotoProfil.mimes'               => 'Format gambar harus jpeg, png, atau jpg.',
            'fotoProfil.max'                 => 'Ukuran gambar maksimal 10MB.'
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('fotoProfil')) {
            if ($user->fotoProfil && Storage::disk('public')->exists(str_replace('storage/', '', $user->fotoProfil))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->fotoProfil));
            }

            $path = $request->file('fotoProfil')->store('profile_photos', 'public');
            $user->fotoProfil = 'storage/' . $path;
        }

        if ($request->filled('desaId')) {
            $this->syncWilayah($request->desaId);
            $user->desaId = $request->desaId;
        }

        $user->email = $request->email;
        $user->detailAlamat = $request->detailAlamat;
        $user->noTelp = $request->noTelp;

        if (!$user->isAdmin && $request->has('namaLengkap')) {
            $user->namaLengkap = $request->namaLengkap;
        }

        $user->save();

        $this->syncBiteshipLocation($user);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    private function syncWilayah(string $desaId)
    {
        try {
            $parts = explode('.', $desaId);
            if (count($parts) !== 4) return;

            $provId = $parts[0];
            $kabId = $parts[0] . '.' . $parts[1];
            $kecId = $parts[0] . '.' . $parts[1] . '.' . $parts[2];

            $resProv = Http::get("https://wilayah.id/api/provinces.json")->json()['data'] ?? [];
            $provName = collect($resProv)->firstWhere('code', $provId)['name'] ?? '';

            $resKab = Http::get("https://wilayah.id/api/regencies/{$provId}.json")->json()['data'] ?? [];
            $kabName = collect($resKab)->firstWhere('code', $kabId)['name'] ?? '';

            $resKec = Http::get("https://wilayah.id/api/districts/{$kabId}.json")->json()['data'] ?? [];
            $kecName = collect($resKec)->firstWhere('code', $kecId)['name'] ?? '';

            $resDesa = Http::get("https://wilayah.id/api/villages/{$kecId}.json")->json()['data'] ?? [];
            $desaName = collect($resDesa)->firstWhere('code', $desaId)['name'] ?? '';

            if ($provName && $kabName && $kecName && $desaName) {
                Provinsi::updateOrCreate(['id' => $provId], ['namaProvinsi' => $provName]);
                Kabupaten::updateOrCreate(['id' => $kabId], ['provinsiId' => $provId, 'namaKabupaten' => $kabName]);
                Kecamatan::updateOrCreate(['id' => $kecId], ['kabupatenId' => $kabId, 'namaKecamatan' => $kecName]);
                Desa::updateOrCreate(['id' => $desaId], ['kecamatanId' => $kecId, 'namaDesa' => $desaName]);

                $this->syncBiteshipArea($desaId, $kecName);
            }

        } catch (\Exception $e) {
            Log::error("Sync Wilayah Gagal: " . $e->getMessage());
        }
    }

    private function syncBiteshipArea(string $desaId, string $kecamatanName)
    {
        try {
            $apiKey = config('services.biteship.key');
            $baseUrl = $this->getBiteshipBaseUrl();

            $exists = DB::table('biteship_areas')->where('desaId', $desaId)->exists();
            if ($exists) return;

            $response = Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/maps/areas", [
                    'countries' => 'ID',
                    'input' => $kecamatanName,
                    'type' => 'single'
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (!empty($areas)) {
                    $area = $areas[0];
                    DB::table('biteship_areas')->updateOrInsert(
                        ['desaId' => $desaId],
                        [
                            'biteship_area_id' => $area['id'],
                            'biteship_name' => $area['name'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    if (!empty($area['postal_code'])) {
                        DB::table('desas')->where('id', $desaId)->update([
                            'kodePos' => $area['postal_code']
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Sync Biteship Area Gagal: " . $e->getMessage());
        }
    }

    public function syncBiteshipLocation(User $user)
    {
        if (!$user->desaId || !$user->detailAlamat) {
            return;
        }

        $apiKey = config('services.biteship.key');
        $baseUrl = $this->getBiteshipBaseUrl();

        if (empty($apiKey)) {
            Log::warning("Biteship API Key is empty. Skipping location sync.");
            return;
        }

        $kecName = $user->kecamatan?->namaKecamatan ?? 'Patrang';
        $postalCode = '68111';

        try {
            $response = Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/maps/areas", [
                    'countries' => 'ID',
                    'input' => $kecName,
                    'type' => 'single'
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (!empty($areas)) {
                    $postalCode = $areas[0]['postal_code'] ?? '68111';
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to fetch postal code from Biteship: " . $e->getMessage());
        }

        $lat = -8.1724;
        $lng = 113.7005;

        try {
            $fullAddress = $user->alamatLengkap;
            $geocodeQuery = urlencode($fullAddress);
            $geoResponse = Http::withHeaders([
                'User-Agent' => 'AgrisApp/1.0 (agrisagroindustri@gmail.com)'
            ])->timeout(5)->get("https://nominatim.openstreetmap.org/search?q={$geocodeQuery}&format=json&limit=1");

            if ($geoResponse->successful()) {
                $geoData = $geoResponse->json();
                if (!empty($geoData)) {
                    $lat = floatval($geoData[0]['lat']);
                    $lng = floatval($geoData[0]['lon']);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Geocoding failed, using fallback coordinates: " . $e->getMessage());
        }

        $type = $user->isAdmin ? 'origin' : 'destination';
        $name = $user->isAdmin ? 'Alamat Penjemputan Admin' : 'Alamat Pengiriman Agen';

        $payload = [
            'name' => $name,
            'contact_name' => $user->namaLengkap ?? ($user->isAdmin ? 'Admin Utama' : 'User AGRIS'),
            'contact_phone' => $user->noTelp,
            'address' => $user->alamatLengkap,
            'postal_code' => strval($postalCode),
            'latitude' => (double) $lat,
            'longitude' => (double) $lng,
            'type' => $type
        ];

        try {
            if ($user->biteship_location_id) {

                $response = Http::withToken($apiKey)
                    ->timeout(10)
                    ->patch("$baseUrl/locations/{$user->biteship_location_id}", $payload);

                if ($response->successful()) {
                    Log::info("Biteship Location Updated successfully: " . $user->biteship_location_id);
                    return;
                } else {
                    $status = $response->status();
                    Log::warning("Biteship Location Update failed with status {$status}. Retrying as new location. Response: " . $response->body());
                }
            }

            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->post("$baseUrl/locations", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $locationId = $data['id'] ?? null;
                if ($locationId) {
                    $user->fill(['biteship_location_id' => $locationId]);
                    $user->save();
                    Log::info("Biteship Location Created successfully: " . $locationId);
                }
            } else {
                Log::error("Biteship Location Creation failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Biteship Location Sync Exception: " . $e->getMessage());
        }
    }

    private function getBiteshipBaseUrl()
    {
        return config('services.biteship.url') ?: 'https://api.biteship.com/v1';
    }
}
