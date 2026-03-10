<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iterinary extends Model
{
    /** @use HasFactory<\Database\Factories\IterinaryFactory> */
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function destinations(){
        return $this->hasMany(Destination::class);
    }
}
