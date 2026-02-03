<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'mobile_number' => [
                'nullable',
                'digits_between:10,15',
                Rule::unique('users', 'mobile_number')->ignore($userId),
            ],
            'is_active' => 'nullable|boolean',
            'roles' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',

            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email already exists.',

            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'mobile_number.digits_between' => 'Mobile number must be between 10 and 15 digits.',
            'mobile_number.unique' => 'This mobile number is already registered.',

            'is_active.boolean' => 'Status must be true or false.',

            'roles.array' => 'Roles must be an array.'
        ];
    }
}
