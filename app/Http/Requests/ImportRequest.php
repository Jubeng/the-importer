<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\+?[\d ()\-\.]{7,15}$/', $value);
        });

        return [
            'first_name'       => 'sometimes|string|regex:/^[a-zA-Z\s\'.-]+$/|min:2|max:50',
            'last_name'        => 'sometimes|string|regex:/^[a-zA-Z\s\'.-]+$/|min:2|max:50',
            'middle_name'      => 'sometimes|string|regex:/^[a-zA-Z\s\'.-]+$/|min:2|max:50',
            'address_street'   => 'sometimes|string|regex:/^[a-zA-Z0-9\s.-]+$/|min:2|max:100',
            'address_brgy'     => 'sometimes|string|regex:/^[a-zA-Z0-9\s.-]+$/|min:2|max:100',
            'address_city'     => 'sometimes|string|regex:/^[a-zA-Z\s.-]+$/|min:2|max:50',
            'address_province' => 'sometimes|string|regex:/^[a-zA-Z\s.-]+$/|min:2|max:50',
            'contact_phone'    => ['sometimes', 'phone_number', 'max:15'],
            'contact_mobile'   => ['sometimes', 'required', 'phone_number', 'min:9', 'max:15'],
            'email'            => [
                'required',
                'email',
                'max:255',
                Rule::unique('import')->ignore($this->import_id, 'import_id')
            ],
        ];
    }
    
    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_phone.phone_number'  => 'Invalid phone number',
            'contact_mobile.phone_number' => 'Invalid mobile number',
            'first_name.regex'            => ':attribute only accepts letters, spaces, -, apostrophe, and period.',
            'last_name.regex'             => ':attribute only accepts letters, spaces, -, apostrophe, and period.',
            'middle_name.regex'           => ':attribute only accepts letters, spaces, -, apostrophe, and period.',
            'address_street.regex'        => ':attribute only accepts letters, numbers, -, period and spaces.',
            'address_brgy.regex'          => ':attribute only accepts letters, numbers, -, period and spaces.',
            'address_city.regex'          => ':attribute only accepts letters, -, period and spaces.',
            'address_province.regex'      => ':attribute only accepts letters, -, period and spaces.',
        ];
    }
}
