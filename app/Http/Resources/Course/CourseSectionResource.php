<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
  public function toArray(Request $request): array
    {
        return [
            'id' => $this->section_id,
            'courseId' => $this->course_id,
            'termId' => $this->term_id,
            'FacultyId' => $this->faculty_id,
            'sectionNumber' => $this->section_number,
            'currentEnrollment' => $this->current_enrollment,
            'content' => $this->content,
            
        ];
    }
}
