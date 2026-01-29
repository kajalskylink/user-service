<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('permissions')->where(function ($query) {
                    return $query->where('guard_name', $this->guard_name);
                }),
            ],
            'group_name' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Permission name is required.',
            'name.unique' => 'This permission name already exists for the selected guard.',
            'group_name.required' => 'Permission group name is required.',
        ];
    }
}
