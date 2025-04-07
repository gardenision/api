<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GardenDevice extends Model
{
    /** @use HasFactory<\Database\Factories\GardenDeviceFactory> */
    use HasFactory;

    protected $fillable = [
        'garden_id',
        'device_id',
    ];

    public function modules()
    {
        return $this->hasMany(GardenDeviceModule::class, 'garden_device_id', 'id');
    }
}
