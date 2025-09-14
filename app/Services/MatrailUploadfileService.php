<?php
namespace App\Services;

use App\Models\Assignment;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Log as log;
use Illuminate\Support\Facades\Storage;
class MatrailUploadfileService

{
    public function uploadFile($file)
    {
        try {
            $path = $file->store('course_materials', 'public');
            return $path;
        } catch (\Exception $e) {
            log::error('File upload error: ' . $e->getMessage());
            throw new \Exception('Failed to upload file');
        }
    }

    public function saveFileRecord($sectionId, $filePath, $request)
    {
        try {
            log::info('Saving file record to database', ['section_id' => $sectionId, 'file_path' => $filePath]);
            $material = CourseMaterial::create([
                'section_id' => $sectionId,
                'file_path' => $filePath,
                'uploaded_date' => now(),
                'uploaded_by' => auth()->user()->username,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
            ]);
             log::info('File record saved successfully', ['material' => $material]);
            return $material;
        } catch (\Exception $e) {
            log::error('Database error: ' . $e->getMessage());
            // Optionally delete the uploaded file if DB save fails
            Storage::disk('public')->delete($filePath);
            throw new \Exception('Failed to save file record');
        }
    }
    public function crerateAssignment($sectionId, $request){
        try{
            log::info('Creating Assignment/Exam', ['section_id' => $sectionId, 'title' => $request->input('title')]);
            $Assignment= Assignment::create([
            'section_id' => $sectionId,
            'due_date' => $request->input('due_date'),
            'description' => $request->input('description'),
            'title' => $request->input('title'),
            'type' => $request->input('type'),
        ]);
            log::info('Assignment/Exam created successfully', ['section_id' => $sectionId, 'title' => $request->input('title')]);
            return $Assignment ;     
          }
        catch(\Exception $e){
            log::error('Database error: ' . $e->getMessage());
            throw new \Exception('Failed to create Assignment or Exam ');
        }
       
    }
    public function getMaterialsBySection($sectionId)
    {
        try {
            log::info('Fetching materials for section', ['section_id' => $sectionId]);
            $materials = CourseMaterial::where('section_id', $sectionId)->get();
            log::info('Materials fetched successfully', ['count' => $materials->count()]);
            return $materials;
        } catch (\Exception $e) {
            log::error('Database error: ' . $e->getMessage());
            throw new \Exception('Failed to fetch materials');
        }
    }
    public function getAssignmentsBySection($sectionId)
    {
        try {
            log::info('Fetching assignments for section', ['section_id' => $sectionId]);
            $assignments = Assignment::where('section_id', $sectionId)->get();
            log::info('Assignments fetched successfully', ['count' => $assignments->count()]);
            return $assignments;
        } catch (\Exception $e) {
            log::error('Database error: ' . $e->getMessage());
            throw new \Exception('Failed to fetch assignments');
        }
    }
    public function getMaterialById($materialId)
    {
        try {
            log::info('Fetching material by ID', ['material_id' => $materialId]);
            $material = CourseMaterial::findOrFail($materialId);
            log::info('Material fetched successfully', ['material' => $material]);
            return $material;
        } catch (\Exception $e) {
            log::error('Database error: ' . $e->getMessage());
            throw new \Exception('Failed to fetch material');
        }
    }
}