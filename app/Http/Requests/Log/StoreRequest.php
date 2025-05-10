<?php

namespace App\Http\Requests\Log;

use App\Models\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! $this->input('device')) {
            return false;
        }

        if (! $this->route('module')) {
            return false;
        }

        if (! $this->input('garden_device_module')) {
            return false;
        }

        return Gate::forUser($this->input('device'))->allows('create', [Log::class, $this->route('module'), $this->input('garden_device_module')]);
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
