<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    /** @use HasFactory<\Database\Factories\DishFactory> */
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'restaurant',
        'description',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
