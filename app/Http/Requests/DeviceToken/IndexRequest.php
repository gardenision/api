<?php

namespace App\Http\Requests\DeviceToken;

use App\Models\Device;
use App\Models\DeviceToken;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! $this->route('device')) {
            return false;
        }
        
        $device = $this->route('device');
        if (! $device) {
            return false;
        }
        
        return $this->user()->can('viewAny', DeviceToken::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
