<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

trait LogUtil
{
    private static $logging;

    protected function log(string $message)
    {
        if (! static::$logging) {
            static::$logging = Log::channel('daily');
        }

        $logid = request()->get('logid');

        if (! $logid) {
            $logid = uniqid();
            request()->merge(['logid' => $logid]);
        }

        static::$logging->info($message, ['uniqid' => $logid]);
    }
}