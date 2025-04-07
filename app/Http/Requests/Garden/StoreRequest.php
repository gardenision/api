<?php
namespace App\Http\Requests\Garden;

use App\Models\Garden;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Garden::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:gardens,name',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
        ];
    }
}
