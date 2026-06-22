<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Model
{
    use HasUlids;

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'pesan',
        'foto_chat',
        'status',
        'waktu_chat'
    ];

    protected $casts = [
        'waktu_chat' => 'datetime',
    ];

    protected $appends = ['foto_chat_url'];

    public function getFotoChatUrlAttribute(): ?string
    {
        return $this->foto_chat ? storage_url($this->foto_chat) : null;
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }

    /**
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
