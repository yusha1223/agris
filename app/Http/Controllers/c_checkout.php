<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class c_checkout extends Controller
{
    public function index(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        if (empty($selectedIds)) return redirect()->route('agen.keranjang.index');

        $items = Keranjang::with('produk.kategori')->whereIn('id', $selectedIds)->userId(Auth::id())->get();
        $totalHarga = 0;
        $totalBeratGram = 0;

        foreach ($items as $item) {
            $totalHarga += ($item->produk->harga * $item->jumlah);
            $totalBeratGram += ($item->produk->kategori->karung * $item->jumlah * 1000);
        }

        $user = Auth::user();
        $admin = User::isAdmin(true)->first();

        return view('agen.checkout.index', compact('items', 'user', 'admin', 'totalHarga', 'totalBeratGram', 'selectedIds'));
    }

    public function getOngkir(Request $request)
    {
        try {
            $user = Auth::user();
            $admin = User::isAdmin(true)->with('desa.kecamatan')->first();

            $originKecId = $admin->desa->kecamatanId ?? null;
            $destKecId = $user->desa->kecamatanId ?? null;

            $originAreaId = $this->getOrSyncBiteshipArea($originKecId);
            $destAreaId = $this->getOrSyncBiteshipArea($destKecId);

            if (!$originAreaId) {
                return response()->json(['error' => 'ID Area Biteship Gudang tidak ditemukan'], 422);
            }
            if (!$destAreaId) {
                return response()->json(['error' => 'ID Area Biteship Tujuan tidak ditemukan. Cek kembali alamat profil Anda.'], 422);
            }

            $response = Http::withHeaders([
                'authorization' => config('services.biteship.key'),
                'content-type' => 'application/json'
            ])->post(rtrim(config('services.biteship.url'), '/') . '/rates/couriers', [
                    'origin_area_id' => $originAreaId,
                    'destination_area_id' => $destAreaId,
                    'couriers' => 'jne,sicepat,tiki,anteraja,jnt',
                    'items' => $request->items
                ]);

            $data = $response->json();

            if (isset($data['pricing'])) {
                $exclude = ['motor', 'reguler', 'regular', 'express', 'instant', 'same day', 'halu', 'yes', 'next day'];
                $include = ['cargo', 'truck', 'jtr', 'gokil', 'trucking', 'freight'];

                $data['pricing'] = array_values(array_filter($data['pricing'], function ($rate) use ($exclude, $include) {
                    $name = strtolower($rate['courier_service_name'] ?? '');
                    $code = strtolower($rate['courier_service_code'] ?? '');

                    foreach ($exclude as $keyword) {
                        if (str_contains($name, $keyword) || str_contains($code, $keyword)) {
                            return false;
                        }
                    }

                    foreach ($include as $keyword) {
                        if (str_contains($name, $keyword) || str_contains($code, $keyword)) {
                            return true;
                        }
                    }

                    return false;
                }));
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getOrSyncBiteshipArea(int|null $kecamatanId): ?string
    {
        if (!$kecamatanId) return null;

        $kec = DB::table('kecamatans')->where('id', $kecamatanId)->first();
        if (!$kec) return null;

        if (!empty($kec->biteship_area_id)) {
            return $kec->biteship_area_id;
        }

        try {
            $kab = DB::table('kabupatens')->where('id', $kec->kabupatenId)->first();
            $cleanKec = trim(str_ireplace(['Kecamatan', 'Kec.'], '', $kec->namaKecamatan));
            $cleanKab = $kab ? trim(str_ireplace(['Kabupaten', 'Kab.', 'Kota', 'Kab'], '', $kab->namaKabupaten)) : '';

            $searchString = "{$cleanKec}, {$cleanKab}";
            $headers = ['authorization' => config('services.biteship.key'), 'Accept' => 'application/json'];
            $url = 'https://api.biteship.com/v1/maps/areas';

            $attempts = [
                ['input' => $searchString, 'type' => 'district'],
                ['input' => $searchString],
                ['input' => $cleanKec, 'type' => 'district']
            ];

            foreach ($attempts as $params) {
                $res = Http::withHeaders($headers)->get($url, array_merge(['countries' => 'ID'], $params));
                if ($res->successful()) {
                    $areas = $res->json()['areas'] ?? [];
                    if (!empty($areas)) {
                        $foundId = null;
                        foreach ($areas as $a) {
                            if (isset($a['id']) && ($a['type'] ?? '') === 'district') {
                                $foundId = $a['id'];
                                break;
                            }
                        }
                        $foundId = $foundId ?? ($areas[0]['id'] ?? null);
                        if ($foundId) {
                            DB::table('kecamatans')->where('id', $kecamatanId)->update(['biteship_area_id' => $foundId]);
                            return $foundId;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Gagal sinkronisasi otomatis Biteship ID: " . $e->getMessage());
        }

        return null;
    }

    public function bayar(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();

        $params = [
            'transaction_details' => ['order_id' => 'AGR-' . time(), 'gross_amount' => (int) $request->total_bayar],
            'customer_details' => [
                'first_name' => $user->namaLengkap ?? $user->username,
                'email' => $user->email,
                'phone' => $user->noTelp,
            ],
        ];

        return response()->json(['token' => Snap::getSnapToken($params)]);
    }
}
