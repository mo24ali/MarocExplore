<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'nom',
        'description',
        'start_time',
        'end_time',
        'cost',
        'currency',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nom'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        if ($value === null) {
            unset($this->attributes['nom']);
        } else {
            $this->attributes['nom'] = $value;
        }
    }
}
