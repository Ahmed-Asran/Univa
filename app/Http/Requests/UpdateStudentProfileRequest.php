<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class UpdateStudentProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user(); // same as auth()->user()
        $routeId = $this->route('id'); // {id} from the route
        if($user->hasRole('student') && $user->user_id == $routeId){
            return true;
        }
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
            'phone' => [
            'sometimes','string',
            Rule::unique('students','phone')->ignore($this->student_id, 'id')
        ],
            "address" => ["sometimes", "string", "max:255"],
          'email' => [
            'sometimes',
            'string',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->user_id, 'user_id'),
        ], 
        ];
    }
      protected function passedValidation()
{
    log::info('Validation passed for CreateUserRequest', $this->validated());
}
protected function failedValidation(Validator $validator)
{
    log::warning('Validation failed for update profile', [
        'errors' => $validator->errors()->all(),
        'input' => $this->all()
    ]);

    throw new HttpResponseException(response()->json([
        'message' => $validator->errors()->first()
    ], 422));
}
protected function failedAuthorization(){
    log::warning('Failed authorization for update profile', [
        'input' => $this->all()
    ]);
    throw new HttpResponseException(response()->json([
        'message' => 'You are not authorized to perform this action'
    ], 401));
}
}
