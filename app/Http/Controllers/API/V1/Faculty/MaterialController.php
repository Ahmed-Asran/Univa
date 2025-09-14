<?php

namespace App\Http\Controllers\API\V1\Faculty;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Services\MatrailUploadfile;
use App\Services\MatrailUploadfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as log;
use App\Models\CourseSection;



class MaterialController extends Controller
{
    protected $matrailUploadfile;

    public function __construct(MatrailUploadfileService $matrailUploadfile)
    {
        $this->matrailUploadfile = $matrailUploadfile;
    }
    public function uploadMaterial(Request $request)
    {
        $section=CourseSection::find($request->section_id);
        if (!auth()->check() || !auth()->user()->hasRole('faculty')||$section->faculty_id != auth()->user()->username) {
            return response()->json(['error' => ' you are Unauthorized'], 401);
        }
        log::info('Upload material request received', ['request' => $request->all()]);
        $request->validate([
            'section_id' => 'required|integer|exists:course_sections,section_id',
            'file' => 'required|file|max:10240', // max 10MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:Assignment,LectureNotes,Exam,other',
            'due_date' => 'required_if:type,Assignment,Exam|date|nullable',
        ]);
        log::info('Validation passed');

        $sectionId = $request->input('section_id');
        $file = $request->file('file');
        log::info('File received', ['file' => $file->getClientOriginalName()]);
        try {
            // Upload the file and get the path
            $filePath = $this->matrailUploadfile->uploadFile($file);
            log::info('File uploaded', ['file_path' => $filePath]);

            // Save the file record in the database
            $material = $this->matrailUploadfile->saveFileRecord($sectionId, $filePath, $request);
            log::info('File uploaded and record saved', ['material' => $material]);
            if($request->input('type')=='Assignment'||$request->input('type')=='Exam'){
               $Assignment= $this->matrailUploadfile->crerateAssignment($sectionId, $request);
                log::info('Assignment/Exam created', ['section_id' => $sectionId, 'title' => $request->input('title')]);
                return response()->json(['message' => 'Material  uploaded successfully', 'data' => $material,'Assignment'=>$Assignment], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
