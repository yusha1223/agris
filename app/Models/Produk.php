<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'produks';

    protected $fillable = [ 'kategoriId', 'namaProduk', 'fotoProduk', 'stok', 'harga', 'deskripsi', ];
    protected $casts = [
        'harga' => 'decimal:2',
        'stok'  => 'integer',
    ];

    protected $appends = ['fotoProdukUrl'];

    public function getFotoProdukUrlAttribute(): ?string
    {
        return $this->fotoProduk ? storage_url($this->fotoProduk) : null;
    }

    protected static function booted()
    {
        static::saved(function ($produk) {
            broadcast(new \App\Events\ProdukUpdated($produk));
        });

        static::deleted(function ($produk) {
            broadcast(new \App\Events\ProdukUpdated($produk));
        });

        static::restored(function ($produk) {
            broadcast(new \App\Events\ProdukUpdated($produk));
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategoriId');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'produkId');
    }
}
