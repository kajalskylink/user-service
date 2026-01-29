<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $roleId = $this->route('role')->id;
        return [
            'name' => 'required|unique:roles,name,' . $roleId,
            'permission_ids' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required.',
            'name.unique'   => 'This role name already exists. Please choose a different name.',
            'permission_ids.array' => 'Permissions selection must be a valid list.',
        ];
    }

}
