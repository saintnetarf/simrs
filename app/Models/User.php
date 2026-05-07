<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Guarded(['id'])]
#[Hidden(['id', 'password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'employee_id';
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('employee_id', 'like', "%{$search}%");
        } else {
            return $query;
        }
    }

    public function scopeDoctor($query)
    {
        return $query->where('role', 'Dokter');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'Admin');
    }

    public function scopeSpecialist($query, $specialist)
    {
        if ($specialist) {
            $query->where('specialist', $specialist);
        } else {
            return $query;
        }
    }

    public function room() : BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_code', 'code');
    }

    public function treatments() : HasMany
    {
        return $this->hasMany(Treatment::class, 'doctor_employee_id', 'employee_id');
    }

}
