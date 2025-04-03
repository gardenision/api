<?php
namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('viewAny', Role::class);
    }

    public function rules()
    {
        return [];
    }
}
