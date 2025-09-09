<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["sometimes", "string", "max:255"],
            "email" => ["sometimes", "string", "email", "max:255", "unique:users,email," . $this->user->id],
            "phone" => ["nullable", "string", "max:15"], // Ensure phone number is a string and has a maximum length of 15 characters
            "address" => ["nullable", "string", "max:255"], // Ensure address is a string and has a maximum length of 255 characters
            "position"=>["nullable", "string", "max:255"], // Ensure position is a string and has a maximum length of 255 characters
            "department"=>["nullable", "string", "max:255"], // Ensure department is a string and has a maximum length of 255
        ];
    }
}
