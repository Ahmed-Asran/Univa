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
    public function getMaterials($sectionId)
    {
        $section=CourseSection::find($sectionId);
        if (!auth()->check() || !auth()->user()->hasRole('faculty')||$section->faculty_id != auth()->user()->username) {
            return response()->json(['error' => ' you are Unauthorized'], 401);
        }
        log::info('Get materials request received', ['section_id' => $sectionId]);
        try {
            $materials = $this->matrailUploadfile->getMaterialsBySection($sectionId);
            log::info('Materials retrieved', ['count' => count($materials)]);
            return response()->json(['data' => $materials], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getMaterial($materialId)
    {
        log::info('Get material request received', ['material_id' => $materialId]);
        try {
            $material = $this->matrailUploadfile->getMaterialById($materialId);
            if (!$material) {
                log::warning('Material not found', ['material_id' => $materialId]);
                return response()->json(['error' => 'Material not found'], 404);
            }
            log::info('Material retrieved', ['material' => $material]);
            return response()->json(['data' => $material], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
