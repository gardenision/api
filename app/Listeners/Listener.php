<?php

namespace App\Listeners;

use App\Utils\LogUtil;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Listener
{
    use LogUtil;
    
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
}
