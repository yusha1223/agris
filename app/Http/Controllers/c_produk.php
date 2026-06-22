<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class c_produk extends Controller
{
    public function indexAgen(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->search) {
            $keyword = '%' . $request->search . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('namaProduk', 'like', $keyword)
                  ->orWhereHas('kategori', fn($k) => $k->where('jenisKategori', 'like', $keyword)
                      ->orWhere('mutu', 'like', $keyword)
                      ->orWhere('karung', 'like', $keyword));
            });
        }

        if ($request->jenis) {
            $query->whereHas('kategori', fn($q) => $q->where('jenisKategori', $request->jenis));
        }

        if ($request->mutu) {
            $query->whereHas('kategori', fn($q) => $q->where('mutu', $request->mutu));
        }

        if ($request->karung) {
            $query->whereHas('kategori', fn($q) => $q->where('karung', $request->karung));
        }

        $produks = $query->latest()->paginate(12)->withQueryString();

        $daftarJenis = KategoriProduk::distinct()->pluck('jenisKategori');
        $daftarMutu = KategoriProduk::distinct()->pluck('mutu');
        $daftarKarung = KategoriProduk::distinct()->orderBy('karung', 'asc')->pluck('karung');

        return view('agen.produk.index', compact('produks', 'daftarJenis', 'daftarMutu', 'daftarKarung'));
    }

    public function showAgen(string $id)
    {
        $item = Produk::with('kategori')->findOrFail($id);

        return view('agen.produk.show', compact('item'));
    }

    public function show(string $id)
    {
        $item = Produk::withTrashed()->with('kategori')->findOrFail($id);
        return view('admin.produk.show', compact('item'));
    }

    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->search) {
            $keyword = '%' . $request->search . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('namaProduk', 'like', $keyword)
                  ->orWhereHas('kategori', fn($k) => $k->where('jenisKategori', 'like', $keyword)
                      ->orWhere('mutu', 'like', $keyword)
                      ->orWhere('karung', 'like', $keyword));
            });
        }

        if ($request->jenis) {
            $query->whereHas('kategori', fn($q) => $q->where('jenisKategori', $request->jenis));
        }
        if ($request->mutu) {
            $query->whereHas('kategori', fn($q) => $q->where('mutu', $request->mutu));
        }
        if ($request->karung) {
            $query->whereHas('kategori', fn($q) => $q->where('karung', $request->karung));
        }

        $produks = $query->latest()->paginate(12)->withQueryString();

        $daftarJenis = KategoriProduk::distinct()->pluck('jenisKategori');
        $daftarMutu = KategoriProduk::distinct()->pluck('mutu');
        $daftarKarung = KategoriProduk::distinct()->orderBy('karung', 'asc')->pluck('karung');

        return view('admin.produk.index', compact('produks', 'daftarJenis', 'daftarMutu', 'daftarKarung'));
    }

    public function create()
    {
        $kategoris = KategoriProduk::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaProduk' => 'required|string|max:150',
            'jenis'      => 'required',
            'mutu'       => 'required',
            'karung'     => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:10048',
            'deskripsi'  => 'nullable|string',
        ],[
            'required'  => 'Data harus diisi!',
            'numeric'   => 'Data harus berupa angka!'
        ]);

        try {
            $kategori = KategoriProduk::firstOrCreate([
                'jenisKategori' => $request->jenis,
                'mutu'          => $request->mutu,
                'karung'        => $request->karung,
            ]);

            $data = $request->only(['namaProduk', 'stok', 'harga', 'deskripsi']);
            $data['kategoriId'] = $kategori->id;

            if ($request->hasFile('fotoProduk')) {
                $path = $request->file('fotoProduk')->store('produk', 'public');
                $data['fotoProduk'] = $path;
            }

            Produk::create($data);

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $produk = Produk::withTrashed()->findOrFail($id);
        $kategoris = KategoriProduk::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, string $id)
    {
        $produk = Produk::withTrashed()->findOrFail($id);

        $request->validate([
            'namaProduk' => 'required|string|max:150',
            'jenis'      => 'required',
            'mutu'       => 'required',
            'karung'     => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:10048',
            'deskripsi'  => 'nullable|string',
        ],[
            'required'  => 'Data wajib diisi!',
            'numeric'   => 'Data harus berupa angka!'
        ]);

        try {
            $kategori = KategoriProduk::firstOrCreate([
                'jenisKategori' => $request->jenis,
                'mutu'          => $request->mutu,
                'karung'        => $request->karung,
            ]);

            $data = $request->only(['namaProduk', 'stok', 'harga', 'deskripsi']);
            $data['kategoriId'] = $kategori->id;

            if ($request->hasFile('fotoProduk')) {
                if ($produk->fotoProduk) {
                    $cleanOldPath = ltrim(str_replace('storage/', '', $produk->fotoProduk), '/');
                    if (Storage::disk('local_public')->exists($cleanOldPath)) {
                        Storage::disk('local_public')->delete($cleanOldPath);
                    }
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }
                $path = $request->file('fotoProduk')->store('produk', 'public');
                $data['fotoProduk'] = $path;
            }

            $produk->update($data);

            if ($produk->trashed() && $request->stok > 0) {
                $produk->restore();
            }

            return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.')->withInput();
        }
    }

    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update(['stok' => 0]);
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function trash()
    {
        $produks = Produk::onlyTrashed()->with('kategori')->latest()->get();
        return view('admin.produk.trash', compact('produks'));
    }

    public function restore(string $id)
    {
        $produk = Produk::onlyTrashed()->findOrFail($id);
        $produk->restore();
        return redirect()->route('admin.produk.index')->with('success', 'Produk diaktifkan kembali.');
    }

    public function forceDelete(string $id)
    {
        $produk = Produk::onlyTrashed()->findOrFail($id);

        if ($produk->fotoProduk) {
            $cleanOldPath = ltrim(str_replace('storage/', '', $produk->fotoProduk), '/');
            if (Storage::disk('local_public')->exists($cleanOldPath)) {
                Storage::disk('local_public')->delete($cleanOldPath);
            }
            if (Storage::disk('public')->exists($cleanOldPath)) {
                Storage::disk('public')->delete($cleanOldPath);
            }
        }

        $produk->forceDelete();
        return redirect()->back()->with('success', 'Produk dihapus permanen.');
    }
}
