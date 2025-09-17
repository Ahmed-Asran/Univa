<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use App\Http\Resources\User\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource

{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->user_id,
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email,
            "student" => $this->whenLoaded('student', new StudentResource($this->student)),
            "faculty" => $this->whenLoaded('faculty', new FacultyResource($this->faculty)),
            
        ];
    }
}
