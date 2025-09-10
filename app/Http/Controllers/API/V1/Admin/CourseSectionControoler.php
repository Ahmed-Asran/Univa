<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Requests\Courses\CreateCourseSectionRequest;
use App\Http\Requests\Courses\UpdateCourseSerctionRequest;
use App\Http\Requests\courses\UpdatecourseRequest;
use App\Http\Resources\Course\CourseSectionResource;

class CourseSectionControoler extends Controller
{
   protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function index()
    {
        $courseSections = $this->courseService->getAllCourseSections();
        return  CourseSectionResource::collection($courseSections);
    }
    public function store(CreateCourseSectionRequest $request)
    {
        $courseSection = $this->courseService->createCourseSection($request);
        return new courseSectionResource($courseSection);  
    }
    public function show($id)
    {
        $courseSection = $this->courseService->getCourseSectionById($id);
         return new courseSectionResource($courseSection);
    }
    public function update(UpdateCourseSerctionRequest $request, $id)
    {
        $data = $request->all();
        $courseSection = $this->courseService->updateCourseSection($id, $data);
         return new courseSectionResource($courseSection);
    }
    public function destroy($id)
    {
        $this->courseService->deleteCourseSection($id);
        return response()->json(['message' => 'Course Section deleted successfully']);
    }
    public function currentSections()
    {
       $courseSections= $this->courseService->getSectionsCurrent();
        return  CourseSectionResource::collection($courseSections);
    }
    
}

