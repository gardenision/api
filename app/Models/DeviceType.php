<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'device_type_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'device_type_id', 'id');
    }
}
