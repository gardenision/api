<?php

namespace App\Http\Requests\DeviceSetting;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [Setting::class, $this->route('garden_device')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ];
    }
}
