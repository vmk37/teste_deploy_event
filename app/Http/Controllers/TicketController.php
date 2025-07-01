<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
                       ->where('user_id', Auth::id())
                       ->firstOrFail();

        return view('tickets.ticket', ['ticket' => $ticket]);
    }
}