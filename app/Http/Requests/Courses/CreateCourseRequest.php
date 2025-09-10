<?php

namespace App\Http\Requests\courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class CreateCourseRequest extends FormRequest
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
            "course_name" => "required|string|max:255|unique:courses,course_name",
            "course_code" => "required|string|max:5|unique:courses,course_code",
            "description" => "nullable|string",
            "credit_hours" => "required|integer|min:0|max:3",
            'prerequisites' => 'sometimes|array|exists:courses,course_id',
        ];
    }
    public function messages(): array
    {
        return [
            "course_name.unique" => "Course name must be unique.",
            "course_name.required" => "Course name is required.",
            "course_name.string" => "Course name must be a string.",
            "course_name.max" => "Course name must not exceed 255 characters.",
            "course_code.required" => "Course code is required.",
            "course_code.string" => "Course code must be a string.",
            "course_code.max" => "Course code must not exceed 5 characters.",
            "course_code.unique" => "Course code must be unique.",
            "description.string" => "Description must be a string.",
            "credit_hours.required" => "Credit hours are required.",
            "credit_hours.integer" => "Credit hours must be an integer.",
            "credit_hours.min" => "Credit hours must be at least 0.",
            "credit_hours.max" => "Credit hours must not exceed 3.",
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'course_name' => trim($this->course_name),
            'course_code' => strtoupper(trim($this->course_code)),
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
    protected function failedAuthorization()
    {
        abort(403, 'You are not authorized to make this request.');
    }
    protected function failedValidation(Validator $errors)
    {
        log::warning('Validation failed for CreateCourseRequest', [
            'errors' => $errors->errors()->all(),
            'input' => $this->all()
        ]);
        throw new HttpResponseException(response()->json([
            'message' => $errors->errors()->first()
        ], 422));
    }

}
