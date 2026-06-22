<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Blog extends Model
{
    use HasUlids;

    protected $table = 'blogs';

    protected $fillable = [
        'userId',
        'judulBlog',
        'isiBlog',
        'fotoBlog',
        'tanggalBlog',
    ];

    protected $casts = [
        'tanggalBlog' => 'date',
    ];

    protected $appends = ['fotoBlogUrl'];

    public function getFotoBlogUrlAttribute(): ?string
    {
        return $this->fotoBlog ? storage_url($this->fotoBlog) : null;
    }

    protected static function booted()
    {
        static::saved(function ($blog) {
            broadcast(new \App\Events\BlogUpdated($blog));
        });

        static::deleted(function ($blog) {
            broadcast(new \App\Events\BlogUpdated($blog));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}