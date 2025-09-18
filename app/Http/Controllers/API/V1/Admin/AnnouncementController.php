<?php
namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $request->validate([
            'title' => 'required|max:255|string|unique:announcements,title',
            'content' => 'required|string',
            'course_section_id' => 'nullable',
        ]);
        $data = $request->all();
        $data['author_id'] = $user->user_id;
        $announcement = $this->announcementService->createAnnouncement($data);
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