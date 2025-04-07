<?php

namespace App\Http\Requests\GardenDeviceModule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->garden, $this->garden_device, $this->garden_device_module);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_active' => 'boolean',
            'unit_value' => 'nullable|string|max:255',
            'unit_type' => 'nullable|string|max:100',
        ];
    }
}
