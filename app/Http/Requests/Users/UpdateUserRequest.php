<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
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
        return [
            "email" => ["sometimes", "string", "email", "max:255",Rule::unique('users', 'email')->ignore($this->route('id'), 'user_id'),],
            "phone" => ["nullable", "string", "max:15", "unique:users,phone," . $this->user_id], 
            "address" => ["nullable", "string", "max:255"], 
            "position"=>["nullable", "string", "max:255"], 
            "department"=>["nullable", "string", "max:255","exists:departments,id"], 
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
