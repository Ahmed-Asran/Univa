<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        log::info("User is authorized to make this request.");
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
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "string", "min:6", "confirmed"], // Ensure password confirmation
            "role" => ["required", "string", "in:admin,student,faculty"], // Ensure role is one of the predefined roles
            "phone" => ["nullable", "string", "max:15","unique:students"], // Ensure phone number is a string and has a maximum length of 15 characters
            "address" => ["nullable", "string", "max:255"], // Ensure address is a string and has a maximum length of 255 characters
            "date_of_birth" => ["nullable", "date"], // Ensure date_of_birth is a valid date
            "position"=>["nullable", "string", "max:255"], // Ensure position is a string and has a maximum length of 255 characters
            "department"=>["nullable", "string", "max:255"], // Ensure department is a string and has a maximum length of 255 
        ];
    }
    public function messages(){
        return [
            "email.unique"=>"A user with this email already exists.",
            "phone.unique"=>"A student with this phone number already exists."
        ];
    }
    protected function passedValidation()
{
    log::info('Validation passed for CreateUserRequest', $this->validated());
}
protected function failedValidation(Validator $validator)
{
    log::warning('Validation failed for CreateUserRequest', [
        'errors' => $validator->errors()->all(),
        'input' => $this->all()
    ]);

    throw new HttpResponseException(response()->json([
        'message' => $validator->errors()->first()
    ], 422));
}
}
