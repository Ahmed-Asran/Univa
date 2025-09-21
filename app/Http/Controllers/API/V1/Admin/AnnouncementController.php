<?php
namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Notifications\NotificationHelper;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AnnouncementController extends Controller
{
    protected $announcementService;
    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }
    public function index()
    {
        $announcements = $this->announcementService->getAllAnnouncement();
        return $announcements;
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        try{
        $validated = $request->validate([
            'title' => 'required|max:255|string|unique:announcements,title',
            'content' => 'required|string',
            'course_section_id' => 'nullable',
        ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['errors' => $e->errors()], 422);
        }
        $data = $validated;
        $data['author_id'] = $user->user_id;
        $announcement = $this->announcementService->createAnnouncement($data);
        if($announcement->course_section_id){
            log::info($announcement->course_section_id);
            $course_section = $announcement->course_section;
            log::info($course_section);
             $users = User::whereHas('student.enrollments', function ($q) use ($announcement) {
                    $q->where('section_id', $announcement->course_section_id);
                })->get();
            log::info($users);
            NotificationHelper::notify($users, 'New announcement', 'A new announcement has been published: '.$announcement->title );
        }
        $users = User::whereHas('student')->get();
        log::info($users);
        NotificationHelper::notify
        ($users, 
        'New announcement', 'A new announcement has been published: '.$announcement->title );
        return $announcement;
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|max:255|string',
            'content' => 'sometimes|string',
            'course_section_id' => 'nullable',
        ]);
        $data = $request->all();
        $announcement = $this->announcementService->updateAnnouncement($id, $data);
        return $announcement;
    }
    public function destroy($id)
    {
        $announcement = $this->announcementService->deleteAnnouncement($id);
        return $announcement;
    }
    public function getAnnouncement($id)
    {
        $announcement = $this->announcementService->getAnnouncement($id);
        return $announcement;
    }
    public function getAnnouncementsForSection($course_section_id)
    {
        $announcements = $this->announcementService->getAnnouncementsForSection($course_section_id);
        return $announcements;
    }
    public function getAllGeneralAnnouncement(){
        log::info('getAllGeneralAnnouncement');
        $announcements = $this->announcementService->getAllGeneralAnnouncement();
        return $announcements;
    }


}