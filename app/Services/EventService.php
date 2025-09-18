<?php 
namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class EventService{
    public function getEvents(){
        $events = CalendarEvent::all();
        return $events;
    }
    public function createEvent($data){
        $event = CalendarEvent::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'event_type' => $data['event_type'],
            'created_by' => $data['created_by'],
        ]);
        return $event;
    }
    public function updateEvent($event_id,$data){
        $event=CalendarEvent::where('event_id',$event_id)->first();
        if($event==null){
            return response()->json(["message"=>"Event not found"],404);
        }
        $event->update([
            'title' => $data['title']??$event->title,
            'description' => $data['description']??$event->description,
            'start_date' => $data['start_date']??$event->start_date,
            'end_date' => $data['end_date']??$event->end_date,
            'event_type' => $data['event_type']??$event->event_type,
            'created_by' => $data['created_by']??$event->created_by,
        ]);
        return $event;
    }
    public function deleteEvent($event_id){
        $event=CalendarEvent::where('event_id',$event_id)->first();
        if($event==null){
            return response()->json(["message"=>"Event not found"],404);
        }
        $event->delete();
        return $event;
    }
    public function getEvent($event_id){

        $event=CalendarEvent::where('event_id',$event_id)->first();
        if($event==null){
              return response()->json(["message"=>"Event not found"],404);
        }
        return $event;
    }
}