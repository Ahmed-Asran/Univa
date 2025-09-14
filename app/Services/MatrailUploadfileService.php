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
}