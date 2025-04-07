<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garden extends Model
{
    /** @use HasFactory<\Database\Factories\GardenFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(GardenDevice::class, 'garden_id', 'id');
    }
}
