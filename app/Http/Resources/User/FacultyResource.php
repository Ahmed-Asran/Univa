<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "faculty_id" => $this->faculty_id,
            "user_id" => $this->user_id,
            "position" => $this->position,
            "department_id" => $this->department_id,
            "department" => $this->whenLoaded('department', function () {
                return $this->department ? $this->department->department_name : null;
            }),
        ];
    }
}
