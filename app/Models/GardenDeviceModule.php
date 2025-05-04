<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GardenDeviceModule extends Model
{
    /** @use HasFactory<\Database\Factories\GardenDeviceModuleFactory> */
    use HasFactory;

    protected $fillable = [
        'garden_device_id',
        'module_id',
        'is_active',
        'unit_value',
        'unit_type',
    ];

    public function garden_device()
    {
        return $this->belongsTo(GardenDevice::class, 'garden_device_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
