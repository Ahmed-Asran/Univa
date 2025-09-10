<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
  public function toArray(Request $request): array
    {
        return [
            'id' => $this->course_id,
            'CourseName' => $this->course_name,
            'description' => $this->description,
            'CreditHours' => $this->credit_hours,
            'isActive' => $this->is_active,
            'prerequisites' => $this->course_prerequisites->map(function($prerequisite) {
                return [
                    'id' => $prerequisite->prerequisite_course_id,
                    'name' => $prerequisite->prerequisiteCourse ? $prerequisite->prerequisiteCourse->course_name : null,
                ];
            }),
        ];
    }
}
