<?php
namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('delete', $this->role);
    }

    public function rules()
    {
        return [];
    }
}
