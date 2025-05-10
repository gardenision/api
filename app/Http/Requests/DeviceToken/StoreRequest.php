<?php

namespace App\Http\Requests\DeviceToken;

use App\Models\Device;
use App\Models\DeviceToken;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! $this->route('device')) {
            return false;
        }

        $device = Device::find($this->route('device'));
        if (! $device) {
            return false;
        }

        return $this->user()->can('create', DeviceToken::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        ];
    }
}
