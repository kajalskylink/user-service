<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|min:11|unique:suppliers,mobile_number',
            'previous_due' => 'nullable|numeric|min:0',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'village' => 'nullable|string',
            'note' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'name.string' => 'Supplier name must be a valid text.',
            'name.max' => 'Supplier name may not be greater than 255 characters.',

            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.numeric' => 'Mobile number must contain only digits.',
            'mobile_number.min' => 'Mobile number must be at least 11 digits.',
            'mobile_number.unique' => 'This mobile number already exists.',

            'previous_due.numeric' => 'Previous due must be a number.',
            'previous_due.min' => 'Previous due cannot be negative.',

            'division_id.exists' => 'Selected division is invalid.',
            'district_id.exists' => 'Selected district is invalid.',
            'upazila_id.exists' => 'Selected upazila is invalid.',
            'union_id.exists' => 'Selected union is invalid.',

            'village.string' => 'Village name must be valid text.',
            'note.string' => 'Note must be valid text.',
        ];
    }
}
