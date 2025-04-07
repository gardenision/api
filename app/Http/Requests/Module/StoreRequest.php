<?php
namespace App\Http\Requests\Module;

use App\Models\DeviceType;
use App\Models\Module;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        $project = Project::find($this->route('project'));
        if (! $project) {
            return false;
        }

        $device_type = DeviceType::find($this->route('device_type'));
        if (! $device_type) {
            return false;
        }

        return $this->user()->can('create', Module::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:modules,name|max:255',
            'type' => 'required|string|max:100|in:sensor,actuator',
            'default_unit_type' => 'required|string|max:100',
            'default_unit_value' => 'required|string|max:255',
        ];
    }
}
