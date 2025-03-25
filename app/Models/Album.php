<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'album_type',
        'cover',
    ];

    public function fotos(): HasMany
    {
        return $this->hasMany(Foto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
