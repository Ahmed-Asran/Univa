<?php
namespace App\Services;
use App\Models\Course;
use Illuminate\Support\Facades\Log as log;
class CourseService
{
    public function createCourse( $data)
    {
        $user=auth()->user();
        log::info($user);
        $course = Course::create([
            'course_name' => $data['course_name'],
            'course_code' => $data['course_code'],
            'description' => $data['description'] ?? null,
            'credit_hours' => $data['credit_hours'],
            'created_by'=>$user->user_id,
    ]);
        
    // Insert prerequisites if any
    if(isset($data['prerequisites']) && is_array($data['prerequisites'])){
        $prerequisites = array_map(fn($preId) => [
            'prerequisite_course_id' => $preId
        ], $data['prerequisites']);

        $course->course_prerequisites()->createMany($prerequisites);
    }

    return $course;
    }
    public function getAllCourses()
    {
        $courses = Course::where(['is_active' => 1,'is_deleted' => 0])->get();
        return $courses;
    }
    public function getCourseById($id)
    {
        $course = Course::find($id);
        if (!$course) {
            throw new \Exception('Course not found');
        }
        if ($course->is_deleted || !$course->is_active) {
            throw new \Exception('Course is inactive or deleted');
        }
        return $course;
    }
   public function updateCourse($id, $data)
{
        $course = Course::find($id);
        if (!$course) {
            throw new \Exception('Course not found');
        }
        if ($course->is_deleted || !$course->is_active) {
            throw new \Exception('Course is inactive or deleted');
        }

        // Update course fields
        $course->update([
            'course_name' => $data['course_name'] ?? $course->course_name,
            'course_code' => $data['course_code'] ?? $course->course_code,
            'description' => $data['description'] ?? $course->description,
            'credit_hours' => $data['credit_hours'] ?? $course->credit_hours,
        ]);

        // Update prerequisites if provided
        if (isset($data['prerequisites']) && is_array($data['prerequisites'])) {
            $newPrerequisites = $data['prerequisites']; // array of course_ids
            $existingPrerequisites = $course->course_prerequisites()->pluck('prerequisite_course_id')->toArray();

            // Find prerequisites to add
            $toAdd = array_diff($newPrerequisites, $existingPrerequisites);
            foreach ($toAdd as $preId) {
                $course->course_prerequisites()->create([
                    'course_id' => $course->course_id,
                    'prerequisite_course_id' => $preId,
                ]);
            }

            // Find prerequisites to remove
            $toRemove = array_diff($existingPrerequisites, $newPrerequisites);
            if (!empty($toRemove)) {
                $course->course_prerequisites()
                    ->whereIn('prerequisite_course_id', $toRemove)
                    ->delete();
            }
        }

        return $course->load('course_prerequisites.prerequisiteCourse'); // eager load updated prereqs
    }
    public function deleteCourse($id)
    {
        $course = Course::find($id);
        if (!$course) {
            throw new \Exception('Course not found');
        }
        if ($course->is_deleted) {
            throw new \Exception('Course is already deleted');
        }
        $course->is_deleted = 1;
        $course->is_active = 0;
        $course->save();
        return true;
    }

}