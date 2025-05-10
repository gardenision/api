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
        'name',
    ];

    public function modules()
    {
        return $this->hasMany(GardenDeviceModule::class, 'garden_device_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function garden()
    {
        return $this->belongsTo(Garden::class, 'garden_id');
    }
}
