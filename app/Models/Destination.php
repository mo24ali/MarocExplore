<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'lieu_logement',
        'image',
        'places',
        'activities',
        'iterinary_id',
    ];

    protected $casts = [
        'places' => 'array',
        'activities' => 'array',
    ];

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    public function iterinary(){
        return $this->belongsTo(Iterinary::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
}
