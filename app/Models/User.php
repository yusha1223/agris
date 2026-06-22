<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUlids;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'password',
        'namaLengkap',
        'email',
        'noTelp',
        'fotoProfil',
        'detailAlamat',
        'isAdmin',
        'isActive',
        'desaId',
        'biteship_location_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['alamatLengkap', 'fotoProfilUrl'];

    public function getFotoProfilUrlAttribute(): string
    {
        return $this->fotoProfil ? storage_url($this->fotoProfil) : '';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'tanggalLahir'      => 'date',
            'isAdmin'           => 'boolean',
            'isActive'          => 'boolean',
        ];
    }


    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desaId', 'id');
    }


    public function getKecamatanAttribute()
    {
        return $this->desa?->kecamatan;
    }

    public function getKabupatenAttribute()
    {
        return $this->desa?->kecamatan?->kabupaten;
    }

    public function getProvinsiAttribute()
    {
        return $this->desa?->kecamatan?->kabupaten?->provinsi;
    }


    public function getAlamatLengkapAttribute(): string
    {
        if (!$this->desa) {
            return $this->detailAlamat ?? '';
        }

        $parts = [
            $this->detailAlamat,
            $this->desa->namaDesa,
            $this->kecamatan?->namaKecamatan,
            $this->kabupaten?->namaKabupaten,
            $this->provinsi?->namaProvinsi,
        ];

        return collect($parts)->filter()->implode(', ');
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeIsAdmin(Builder $query, bool $value): Builder
    {
        return $query->where('isAdmin', $value);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeIsActive(Builder $query, bool $value): Builder
    {
        return $query->where('isActive', $value);
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'userId');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
