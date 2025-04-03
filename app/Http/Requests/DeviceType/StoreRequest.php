<?php
namespace App\Http\Requests\DeviceType;

use App\Models\DeviceType;
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

        return $this->user()->can('create', DeviceType::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:device_types,name|max:255',
        ];
    }
}
