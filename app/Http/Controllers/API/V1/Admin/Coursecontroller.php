<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\CreateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;

class Coursecontroller extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function store(CreateCourseRequest $request)
    {
        $data = $request->validated();
        $course = $this->courseService->createCourse($data);
        return new CourseResource($course);
    }
    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return CourseResource::collection($courses);
    }  
    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return new CourseResource($course);
    }  
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $course = $this->courseService->updateCourse($id, $data);
        return new CourseResource($course);
    }
    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
