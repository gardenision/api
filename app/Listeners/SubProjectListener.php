<?php

namespace App\Listeners;

use App\Events\SubProject;
use App\Models\Analytic;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubProjectListener
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
    public function handle(SubProject $event): void
    {
        $total_projects = Analytic::where('type', '=', 'total_projects')->first();

        if (! $total_projects) {
            // count projects
            $total_projects = Project::count();

            $total_projects = Analytic::create([
                'type' => 'total_projects',
                'data' => [
                    'value' => $total_projects,
                ],
            ]);
        } else {
            $total = (int) $total_projects->data['value'];
            $total = is_int($total) && $total > 1 ? $total - 1 : 0;
            $total_projects->data = ['value' => $total];
            $total_projects->save();
        }
    }
}
