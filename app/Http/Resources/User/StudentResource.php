<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "student_id" => $this->student_id,
            "user_id" => $this->user_id,
            "phone" => $this->phone,
            "address" => $this->address,
            "birthday" => $this->birthday,
            "current_gpa" => $this->current_gpa,
            "total_credits" => $this->total_credits,
            "level" => $this->level,  
        ];
    }
}
