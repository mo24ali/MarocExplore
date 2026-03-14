<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stay extends Model
{
    /** @use HasFactory<\Database\Factories\StayFactory> */
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'address',
        'description',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
