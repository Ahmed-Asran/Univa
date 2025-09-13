<?php
namespace App\Services;
use App\Models\Course;
use Illuminate\Support\Facades\Log as log;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
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
    public function createCourseSection($data)
    {
        DB::beginTransaction();
        try {
        $courseSection = CourseSection::create([
            'course_id' => $data['course_id'],
            'term_id' => $data['term_id'],
            'faculty_id' => $data['faculty_id'],
            'section_number' => $data['section_number'],
            'content' => $data['content'] ?? null,
        ]);
        DB::commit();
        return $courseSection;
        
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpResponseException(response()->json([
                ['message' => 'create course section failed']
            ], 400));
        }
    }
    public function getAllCourseSections()
    {
        return CourseSection::where(['is_deleted' => 0])->get();
    }
    public function getCourseSectionById($id)
    {
        $courseSection = CourseSection::find($id);
        if (!$courseSection) {
            throw new \Exception('Course Section not found');
        }
        if ($courseSection->is_deleted) {
            throw new \Exception('Course Section is inactive or deleted');
        }
        return $courseSection;
    }public function updateCourseSection($id, $data)
    {
        $courseSection = CourseSection::find($id);
        if (!$courseSection) {
            throw new \Exception('Course Section not found');
        }

        $courseSection->update([
            'course_id' => $data['course_id'] ?? $courseSection->course_id,
            'term_id' => $data['term_id'] ?? $courseSection->term_id,
            'faculty_id' => $data['faculty_id'] ?? $courseSection->faculty_id,
            'section_number' => $data['section_number'] ?? $courseSection->section_number,
            'content' => $data['content'] ?? $courseSection->content,
        ]);

        return $courseSection;
    }
    public function deleteCourseSection($id)
    {
        $courseSection = CourseSection::find($id);
        if (!$courseSection) {
            throw new \Exception('Course Section not found');
        }
        $courseSection->is_deleted = 1;
        $courseSection->save();
        return true;
    }
    public function getSectionsCurrent()
    {
       return CourseSection::where(['is_deleted' => 0])
        ->whereHas('academic_term', fn($query) => $query->where('is_current', 1))
        ->get();
    }

}