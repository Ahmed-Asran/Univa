<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;    
use App\Services\SupportTicketService;
use Illuminate\Http\Request;    

class SupportTicketController extends Controller
{
    protected $SupportTicketService;
    public function __construct(SupportTicketService $service)
    {
        $this->SupportTicketService = $service;
    }
    public function index()
    {
        return $this->SupportTicketService->getSupportTickets();
    }
    public function show($id)
    {
        return $this->SupportTicketService->getSupportTicket($id);
    }
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'status' => 'required|string|in:Open,Closed,In Progress,Resolved',
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return $this->SupportTicketService->updateSupportTicket($id,$request->all());
    }
}
