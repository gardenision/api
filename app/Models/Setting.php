<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'settingable_id',
        'settingable_type',
        'key',
        'value',
        'type',
        'active',
        'last_actived_at',
        'last_inactived_at',
    ];

    public function settingable()
    {
        return $this->morphTo();
    }
}
