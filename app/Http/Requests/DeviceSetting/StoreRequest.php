<?php

namespace App\Http\Requests\DeviceSetting;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', [Setting::class, $this->route('garden_device')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'value' => ['string', 'max:255'],
            'type' => ['string', 'max:255'],
            'active' => ['boolean'],
        ];
    }
}
