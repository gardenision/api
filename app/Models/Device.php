<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'device_type_id',
        'name',
        'serial_number',
    ];

    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }
}
