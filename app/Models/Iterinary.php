<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iterinary extends Model
{
    /** @use HasFactory<\Database\Factories\IterinaryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'duration',
        'image',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function destinations(){
        return $this->hasMany(Destination::class);
    }

    public function getCategoryAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return $this->attributes['catégorie'] ?? null;
    }

    public function setCategoryAttribute($value)
    {
        // $this->attributes['category'] = $value;
        $this->attributes['catégorie'] = $value;
    }

    public function getDurationAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return $this->attributes['duree'] ?? null;
    }

    public function setDurationAttribute($value)
    {
        // $this->attributes['duration'] = $value;
        $this->attributes['duree'] = $value;
    }
}
