<?php

namespace App\Http\Requests\Device;

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

        return $this->user()->can('update', $this->device);
    }

    public function rules()
    {
        return [
            'name' => [
                'string', 'max:255',
                Rule::unique('devices', 'name')->ignore($this->device->id),
            ],
            'serial_number' => [
                'string', 'max:255',
                Rule::unique('devices', 'serial_number')->ignore($this->device->id),
            ],
        ];
    }
}
