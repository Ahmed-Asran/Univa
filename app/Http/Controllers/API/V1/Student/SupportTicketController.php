<?php

namespace App\Http\Controllers\API\V1\Student;
use App\Http\Controllers\Controller;
use App\Services\SupportTicketService;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    protected $SupportTicketService;
    public function __construct(SupportTicketService $service)
    {
        $this->SupportTicketService = $service;
    }
    public function store(Request $request)
    {
        try{
            $request->validate([
                'description' => 'required|string|max:255',
                'subject' => 'required|string',
                'category' => 'nullable|in:Technical Issue,Academic,General',
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
        $user=auth()->user();
        $student=$user->student;
        $request->merge(['student_id'=>$student->student_id]);
        log::info('request data: ', $request->all());
        $suportTicket= $this->SupportTicketService->createSupportTicket($request->all());
        log::info('Support Ticket Created: ', $suportTicket->toArray());
        return $suportTicket;
    }
    public function show($id)
    {
       
        $supportTicket= $this->SupportTicketService->getSupportTicket($id);
        if(!$supportTicket){
            return response()->json(['error' => 'Support Ticket not found'], 404);
        }
        if(auth()->user()->student->student_id!=$supportTicket->student_id){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $supportTicket;
    }
    public function index()
    {
        return $this->SupportTicketService->getAllForStudent(auth()->user()->student->student_id);
    }
}
