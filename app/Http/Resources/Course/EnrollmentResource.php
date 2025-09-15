<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // If the resource is an actual enrollment
        if (isset($this->enrollment_id) || $this instanceof \App\Models\Enrollment) {
            return [
                'enrollment_id' => $this->enrollment_id ?? $this->id,
                'student_id' => $this->student_id,
                'section_id' => $this->section_id,
                'enrollment_date' => $this->enrollment_date,
                'status' => $this->status,
                'final_grade' => $this->final_grade,
                'result' => $this->result,
                'course' => $this->course_section ? [
                    'course_id' => $this->course_section->course->course_id,
                    'course_code' => $this->course_section->course->course_code,
                    'course_name' => $this->course_section->course->course_name,
                    'description' => $this->course_section->course->description,
                    'credit_hours' => $this->course_section->course->credit_hours,
                    'course_prerequisites' => $this->course_section->course->course_prerequisites->map(fn($pre) => [
                        'prerequisite_course_id' => $pre->prerequisite_course_id,
                        'course_name' => $pre->prerequisiteCourse->course_name ?? null,
                    ]),
                ] : null,
                'term' => $this->course_section ? [
                    'term_id' => $this->course_section->term_id,
                    'term_name' => $this->course_section->academic_term->term_name ?? null,
                ] : null,
            ];
        }

        // Otherwise treat it as an available section
        return [
            'section_id' => $this->section_id,
            'course_id' => $this->course_id,
            'term_id' => $this->term_id,
            'faculty_id' => $this->faculty_id,
            'section_number' => $this->section_number,
            'current_enrollment' => $this->current_enrollment,
            'course' => [
                'course_id' => $this->course->course_id,
                'course_code' => $this->course->course_code,
                'course_name' => $this->course->course_name,
                'description' => $this->course->description,
                'credit_hours' => $this->course->credit_hours,
                'course_prerequisites' => $this->course->course_prerequisites->map(fn($pre) => [
                    'prerequisite_course_id' => $pre->prerequisite_course_id,
                    'course_name' => $pre->prerequisiteCourse->course_name ?? null,
                ]),
            ],
            'term' => $this->academic_term ? [
                            'term_id' => $this->academic_term->term_id,
                            'term_name' => $this->academic_term->term_name,
                            ] : [
                                'term_id' => $this->term_id,
                                'term_name' => null,
                            ],
        ];
    }

}
