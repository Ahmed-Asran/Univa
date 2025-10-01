<?php 
namespace App\Services;

use App\Models\SupportTicket;

class SupportTicketService
{
    public function createSupportTicket($data)
    {
        return SupportTicket::create([
            'description'=>$data['description'],
            'student_id'=>$data['student_id'],
            'subject'=>$data['subject'],
        ]);
    }
    public function getSupportTicket($id)
    {
        return SupportTicket::find($id);
    }
    public function getSupportTickets()
    {
        return SupportTicket::all();
    }
    public function updateSupportTicket($id,$data)
    {
       $supportTicket=SupportTicket::find($id);
       if (!$supportTicket) {
        return response ()->json(['error' => 'Support Ticket not found'], 404);
       }
       $supportTicket->update([
        'status'=>$data['status']
       ]);
       return $supportTicket;
    }
    public function getAllForStudent($id)
    {
        return SupportTicket::where('student_id','=',$id)->get();
    }
}