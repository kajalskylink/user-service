<?php

namespace App\Http\Requests\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class CreateBuyerRequest extends FormRequest
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
            'mobile_number' => 'required|numeric|min:11|unique:buyers,mobile_number',
            'division_id' => 'nullable|exists:location_divisions,id',
            'district_id' => 'nullable|exists:location_districts,id',
            'upazila_id' => 'nullable|exists:location_upazilas,id',
            'union_id' => 'nullable|exists:location_unions,id',
            'village' => 'nullable|string',
            'note' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Buyer name is required.',
            'name.string' => 'Buyer name must be a valid text.',
            'name.max' => 'Buyer name may not be greater than 255 characters.',

            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.digits' => 'Mobile number must be exactly 11 digits.',
            'mobile_number.unique' => 'This mobile number is already registered.',

            'division_id.exists' => 'Selected division is invalid.',
            'district_id.exists' => 'Selected district is invalid.',
            'upazila_id.exists' => 'Selected upazila is invalid.',
            'union_id.exists' => 'Selected union is invalid.',

            'village.string' => 'Village name must be valid text.',
            'note.string' => 'Note must be valid text.',
        ];
    }

}
