<?php
namespace App\Http\Requests\DeviceType;

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
        
        return $this->user()->can('update', $this->device_type);
    }

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('device_types', 'name')->ignore($this->device_type->id),
            ],
        ];
    }
}
