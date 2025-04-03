<?php
namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Project;

class DestroyRequest extends FormRequest
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

        return $this->user()->can('delete', $this->device);
    }

    public function rules()
    {
        return [
        ];
    }
}
