<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded(['id'])]
#[Hidden(['id'])]

class Room extends Model
{
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%");
        } else {
            return $query;
        }
    }

    public function users() : HasMany
    {
        return $this->hasMany(User::class, 'room_code', 'code');
    }

}
