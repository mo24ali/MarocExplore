<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'lieu_logement',
        'primary_image',
        'stay_location_id',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function iterinaries()
    {
        return $this->belongsToMany(Iterinary::class, 'destination_iterinaire', 'destination_id', 'itinéraire_id')->withTimestamps();
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }

    public function stay()
    {
        return $this->belongsTo(Stay::class, 'stay_location_id');
    }
}
