<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded(['id'])]
#[Hidden(['id'])]
class Treatment extends Model
{
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function scopeSearch($query, $search)
    {
        if ($search != '') {
            return $query->where('nik', 'LIKE', "%{$search}%")->orWhere('patient_name', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    public function scopeStatus($query, $status)
    {
        if ($status != '') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_employee_id', 'employee_id');
    }
}
