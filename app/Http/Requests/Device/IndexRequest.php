<?php

namespace App\Http\Requests\Device;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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

        return $this->user()->can('viewAny', Device::class);
    }

    public function rules()
    {
        return [
        ];
    }
}
