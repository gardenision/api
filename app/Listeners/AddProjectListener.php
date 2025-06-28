<?php

namespace App\Listeners;

use App\Events\AddProject;
use App\Models\Analytic;
use App\Models\Project;
use App\Utils\LogUtil;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddProjectListener extends Listener
{
    use LogUtil;
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
    public function handle(AddProject $event): void
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
            $total = is_int($total) && $total >= 0 ? $total + 1 : 1;
            $total_projects->data = ['value' => $total];
            $total_projects->save();
        }
    }
}
