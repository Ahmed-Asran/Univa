<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class CraeteEnrollmentRequest extends FormRequest
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
            'enrolments' => 'required|array',
            'enrolments.*.student_id' => 'required|exists:students,student_id',
            'enrolments.*.section_id' => 'required|exists:course_sections,section_id',
            'enrolments.*.enrollment_date' => 'nullable|date',
            'enrolments.*.status' => 'nullable|string',
            'enrolments.*.final_grade' => 'nullable|string',
            'enrolments.*.result' => 'nullable|integer'
        ];
    }
    public function messages()
    {
        return [
            'enrolments.required' => 'The enrolments field is required.',
            'enrolments.array' => 'The enrolments field must be an array.',
            'enrolments.*.student_id.required' => 'Each enrolment must have a student_id.',
            'enrolments.*.student_id.exists' => 'The specified student_id does not exist.',
            'enrolments.*.section_id.required' => 'Each enrolment must have a section_id.',
            'enrolments.*.section_id.exists' => 'The specified section_id does not exist.',
        
            'enrolments.*.enrollment_date.date' => 'The enrollment_date must be a valid date.',
            'enrolments.*.status.string' => 'The status must be a string.',
            'enrolments.*.final_grade.string' => 'The final_grade must be a string.',
            'enrolments.*.result.integer' => 'The result must be an integer.'
        ];
    }
    protected function failedValidation( validator $validator) {
        log::warning('Validation failed', $validator->errors()->toArray());
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first()
        ], 422));
    }
    protected function failedAuthorization() {
        throw new HttpResponseException(response()->json([
            'message' => 'This action is unauthorized.'
        ], 403));
    }
}
