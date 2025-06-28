<?php

namespace App\Listeners;

use App\Events\AddGarden;
use App\Models\Analytic;
use App\Models\Garden;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddGardenListener extends Listener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AddGarden $event): void
    {
        $garden = $event->garden;

        $this->log('AddGardenListener : garden : ' . json_encode($garden));

        $user_id = $garden->user_id ?? null;
        if (! $user_id) {
            $this->log('ERROR AddGardenListener : User not found - garden : ' . json_encode($garden));
            return;
        }

        // total_user_gardens
        $total_user_gardens = Analytic::where('type', '=', 'total_user_gardens')->where('analyticable_type', '=', User::class)->where('analyticable_id', '=', $user_id)->first();

        if (! $total_user_gardens) {
            // count gardens
            $total_user_gardens = Garden::where('user_id', '=', $user_id)->count();

            $total_user_gardens = Analytic::create([
                'type' => 'total_user_gardens',
                'data' => [
                    'value' => $total_user_gardens,
                ],
                'analyticable_type' => User::class,
                'analyticable_id' => $user_id,
            ]);
        } else {
            $total = (int) $total_user_gardens->data['value'];
            $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
            $total_user_gardens->data = ['value' => $total];
            $total_user_gardens->save();
        }
    }
}
