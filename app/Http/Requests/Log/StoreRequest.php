<?php

namespace App\Http\Requests\Log;

use App\Models\Log;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', $this->garden, $this->garden_device, $this->module);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'level' => ['required', 'string'],
            'context' => ['required', 'array'],
            'context.value' => ['required', 'string'],
        ];
    }
}
