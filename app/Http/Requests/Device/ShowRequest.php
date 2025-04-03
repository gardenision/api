<?php

namespace App\Http\Requests\Device;

use App\Models\DeviceType;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = Project::find($this->route('project'));
        if (! $project) {
            return false;
        }

        $device_type = DeviceType::find($this->route('device_type'));
        if (! $device_type) {
            return false;
        }

        return $this->user()->can('view', $this->device);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        ];
    }
}
