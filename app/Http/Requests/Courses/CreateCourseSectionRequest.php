<?php

namespace App\Http\Requests\courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Validation\Rule;

class CreateCourseSectionRequest extends FormRequest
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
            "course_id" => 
            ["required",
            Rule::exists("courses",'course_id')
            ->where(fn($query) => $query->where(['is_active'=>1,'is_deleted'=>0]))],

            "term_id" => ["required",Rule::exists("academic_terms",'term_id')
            ->where(fn($query) => $query->where(['is_current'=>1]))
            ],

            "faculty_id" => ["required",
            Rule::exists("faculty",'faculty_id')
            ->where(fn($query)=> $query->where(['is_deleted'=>0]))],

            "section_number" => "required|string",
            'content' => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            "course_id.required" => "Course is required.", 
            "course_id.exists" => "Course must be exist.",
            "term_id.required" => "Term is required.",
            "term_id.exists" => "Term must be exist.",
            "faculty_id.required" => "Faculty is required.",
            "faculty_id.exists" => "Faculty must be exist.",
            "section_number.required" => "Section number is required.",
            "section_number.string" => "Section number must be a string.",
            "content.string" => "Content must be a string.",
        ];
    }

    protected function failedAuthorization()
    {
        abort(403, 'You are not authorized to make this request.');
    }
    protected function failedValidation(Validator $errors)
    {
        log::warning('Validation failed for CreateCourseSectionRequest', [
            'errors' => $errors->errors()->all(),
            'input' => $this->all()
        ]);
        throw new HttpResponseException(response()->json([
            'message' => $errors->errors()->first()
        ], 422));
    }

}
