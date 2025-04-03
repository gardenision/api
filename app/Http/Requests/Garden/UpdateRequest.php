<?php
namespace App\Http\Requests\Garden;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Garden;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->garden);
    }

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('gardens', 'name')->ignore($this->garden->id),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }
}
