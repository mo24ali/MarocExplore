<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;
    public function activities(){
        return $this->hasMany(Activity::class);
    }
    public function iterinaries(){
        return $this->belongsToMany(Iterinary::class);
    }
}
