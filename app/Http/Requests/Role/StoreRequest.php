<?php
namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Role::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name|max:255',
        ];
    }
}
