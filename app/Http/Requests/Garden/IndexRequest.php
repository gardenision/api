<?php
namespace App\Http\Requests\Garden;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Garden;

class IndexRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('viewAny', Garden::class);
    }

    public function rules()
    {
        return [];
    }
}
