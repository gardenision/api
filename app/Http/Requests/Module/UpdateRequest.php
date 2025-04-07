<?php
namespace App\Http\Requests\Module;

use App\Models\DeviceType;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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

        return $this->user()->can('update', $this->module);
    }

    public function rules()
    {
        return [
            'name' => [
                'string', 'max:255',
                Rule::unique('modules', 'name')->ignore($this->module->id),
            ],
            'type' => 'string|max:100',
            'default_unit_type' => 'string|max:100',
            'default_unit_value' => 'string|max:255',
        ];
    }
}
