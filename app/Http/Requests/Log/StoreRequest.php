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

        $allowed = [
            Log::class
        ];

        if ($this->route('module')) {
            $allowed[] = $this->route('module');
        }

        if ($this->input('garden_device_module')) {
            $allowed[] = $this->input('garden_device_module');
        }

        return Gate::forUser($this->input('device'))->allows('create', $allowed);
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
            'context.value' => ['required_if:level,info', 'string'],
        ];
    }
}
