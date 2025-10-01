<?php
namespace App\Services;

use App\Models\Announcement;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Log;

class AnnouncementService{
    public function createAnnouncement($data){
        $announcement =Announcement::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'author_id' => $data['author_id'],
            'course_section_id' => $data['course_section_id']??null,
        ]);
        return $announcement;
    }
    public function updateAnnouncement($announcement_id,$data){
        $announcement=Announcement::where('announcement_id',$announcement_id)->first();
        $announcement->update([
            'title' => $data['title']??$announcement->title,
            'content' => $data['content']??$announcement->content,
            'author_id' => $data['author_id']??$announcement->author_id,
            'course_section_id' => $data['course_section_id']??$announcement->course_section_id,
        ]);
        return $announcement;
    }
    public function deleteAnnouncement($announcement_id){
        $announcement=Announcement::where('announcement_id',$announcement_id)->first();
        $announcement->delete();
        return $announcement;
    }   
    public function getAnnouncement($announcement_id){
         $announcement=Announcement::where('announcement_id',$announcement_id)->first($announcement_id);
        return $announcement;
    }
    public function getAllAnnouncement(){
        $announcements=Announcement::all();
        return $announcements;
    }
    public function getAllGeneralAnnouncement(){
        $announcements=Announcement::where('course_section_id',null)->get();
        log::info($announcements);
        return $announcements;
    }
    public function getAnnouncementsForSection($course_section_id){
        $announcements=Announcement::where('course_section_id',$course_section_id)->get();;
        return $announcements;
    }
} 