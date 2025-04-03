<?php
namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->role);
    }

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles', 'name')->ignore($this->role->id),
            ],
        ];
    }
}
