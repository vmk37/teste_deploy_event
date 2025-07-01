<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;

class EventDetailController extends Controller
{
   
 public function show($id)
{
    $event = Event::with('users')->findOrFail($id); 

    $user = Auth::user();
    $isUserRegistered = false;

    if ($user) {
        $isUserRegistered = $event->tickets()->where('user_id', $user->id)->exists();
    }

    $eventOrganizer = User::where('id', '=', $event->user_id)->first()->toArray();

    return view('events.show', [
        'event' => $event,
        'eventOrganizer' => $eventOrganizer,
        'isUserRegistered' => $isUserRegistered,
        'participants' => $event->users 
    ]);
}


}
