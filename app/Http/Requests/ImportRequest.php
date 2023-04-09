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
            'first_name'       => 'sometimes|string|min:2|max:50',
            'last_name'        => 'sometimes|string|min:2|max:50',
            'middle_name'      => 'sometimes|string|min:2|max:50',
            'address_street'   => 'sometimes|string|min:2|max:100',
            'address_brgy'     => 'sometimes|string|min:2|max:100',
            'address_city'     => 'sometimes|string|min:2|max:50',
            'address_province' => 'sometimes|string|min:2|max:50',
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
        ];
    }
}
