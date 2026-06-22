<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class c_laporan extends Controller
{
    public function index(Request $request)
    {
        $pemasukanList = Pembayaran::whereNotNull('waktuDibayar')
            ->with('pesanan.user')
            ->get();

        $pengeluaranList = Pembayaran::statusPembayaran('gagal')
            ->whereNotNull('waktuDibayar')
            ->with('pesanan.user')
            ->get();

        $riwayat = [];

        foreach ($pemasukanList as $item) {
            $riwayat[] = [
                'id' => $item->id,
                'pesananId' => $item->pesananId,
                'tanggal' => $item->waktuDibayar ?? $item->updated_at,
                'tipe' => 'pemasukan',
                'deskripsi' => 'Pembayaran Pesanan #'.substr($item->pesananId, 0, 8).' (Pelanggan: '.($item->pesanan->user->namaLengkap ?? 'User').')',
                'nominal' => $item->totalPembayaran,
            ];
        }

        foreach ($pengeluaranList as $item) {
                $riwayat[] = [
                    'id' => $item->id,
                    'pesananId' => $item->pesananId,
                    'tanggal' => $item->updated_at,
                    'tipe' => 'pengeluaran',
                    'deskripsi' => 'Refund Dana Pesanan #'.substr($item->pesananId, 0, 8).' (Pelanggan: '.($item->pesanan->user->namaLengkap ?? 'User').')',
                    'nominal' => $item->totalPembayaran,
                ];
        }

        usort($riwayat, function ($a, $b) {
            return $b['tanggal'] <=> $a['tanggal'];
        });

        $totalPemasukan = array_sum(array_column(array_filter($riwayat, function ($item) {
            return $item['tipe'] === 'pemasukan';
        }), 'nominal'));

        $totalPengeluaran = array_sum(array_column(array_filter($riwayat, function ($item) {
            return $item['tipe'] === 'pengeluaran';
        }), 'nominal'));

        $saldoNeto = $totalPemasukan - $totalPengeluaran;

        return view('admin.laporan.index', compact('riwayat', 'totalPemasukan', 'totalPengeluaran', 'saldoNeto'));
    }
}
