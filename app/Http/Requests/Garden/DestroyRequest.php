<?php
namespace App\Http\Requests\Garden;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Garden;

class DestroyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('delete', $this->garden);
    }

    public function rules()
    {
        return [];
    }
}
