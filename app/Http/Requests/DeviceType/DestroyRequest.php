<?php
namespace App\Http\Requests\DeviceType;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function authorize()
    {
        $project = Project::find($this->route('project'));
        if (! $project) {
            return false;
        }
        
        return $this->user()->can('delete', $this->device_type);
    }

    public function rules()
    {
        return [
        ];
    }
}
