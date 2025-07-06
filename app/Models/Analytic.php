<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    protected $fillable = [
        'analyticable_type',
        'analyticable_id',
        'timestamp',
        'type',
        'data',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function analyticable()
    {
        return $this->morphTo();
    }
}
