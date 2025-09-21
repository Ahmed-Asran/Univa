<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use App\Notifications\NotificationHelper;
use App\Models\User;

class EventController extends Controller
{
    protected $eventService;
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = $this->eventService->getEvents();
        return response()->json($events,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $user = auth()->user();
    try{
    $validated = $request->validate([
        'title' => 'required|string',   
        'description' => 'nullable|string',
        'start_date' => 'required|date|before_or_equal:end_date|after:now',
        'end_date' => 'required|date|after:start_date',
        'event_type' => 'required|string',
    ]);
    }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json(['errors' => $e->errors()], 422);
    }
    

    // Add created_by safely
    $validated['created_by'] = $user->user_id;

    $event = $this->eventService->createEvent($validated);
    $users = User::all(); // Get all users from the database.
    NotificationHelper::notify($users, 'New event', 'A new event has been added: '.$event->title .$event->description);

    return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = $this->eventService->getEvent($id);
          if($event->status()==404){
            return response()->json(["message"=>"Event not found"],404);
        }
        return response()->json($event,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = $this->eventService->updateEvent($id,$request->all());
          if($event->status()==404){
            return response()->json(["message"=>"Event not found"],404);
        }
        return response()->json($event,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = $this->eventService->deleteEvent($id);
        if($event->status()==404){
            return response()->json(["message"=>"Event not found"],404);
        }
        return response()->json(["message"=>"Event deleted successfully","data"=>$event],200);
    }
}
