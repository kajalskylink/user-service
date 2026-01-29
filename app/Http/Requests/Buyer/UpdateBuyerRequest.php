<?php

namespace App\Http\Requests\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuyerRequest extends FormRequest
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
            'mobile_number' => 'nullable|digits:11',
            'previous_due' => 'required|numeric|min:0',
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
            'name.required' => 'Buyer name is required.',
            'name.string' => 'Buyer name must be valid text.',
            'name.max' => 'Buyer name cannot exceed 255 characters.',

            'mobile_number.digits' => 'Mobile number must be exactly 11 digits.',

            'previous_due.required' => 'Previous due amount is required.',
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
