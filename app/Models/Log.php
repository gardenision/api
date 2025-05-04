<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'level',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
