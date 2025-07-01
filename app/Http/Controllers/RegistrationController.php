<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function joinEventConfirm($id)
    {
        $event = Event::findOrFail($id);

        $now = Carbon::now('America/Sao_Paulo');
        $eventDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $event->date_event->format('Y-m-d') . ' ' . $event->time_event,
            'America/Sao_Paulo'
        );

        if ($event->is_expired || $eventDateTime->lte($now)) {
            return redirect()->back()->with('error', 'Este evento já expirou e não aceita novas inscrições.');
        }

        if ($event->participant_limit && $event->users()->count() >= $event->participant_limit) {
            return redirect('/dashboard')->with('error', 'O limite de participantes para este evento já foi atingido.');
        }

        if ($event->price > 0) {
            return redirect()->route('payment.show', $event->id);
        }

        $user = Auth::user();
        $user->participatedEvents()->attach($id);

        $ticketNumber = Str::uuid()->toString();
        $eventLocation = $event->address ?
            "{$event->address->street}, {$event->address->addressNumber}, {$event->address->neighborhood}, {$event->address->municipality} - {$event->address->state}" :
            'Local não especificado';

        Ticket::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'ticket_number' => $ticketNumber,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_cpf' => $user->cpf ?? 'Não informado',
            'event_headline' => $event->headline,
            'event_date' => $eventDateTime,
            'event_location' => $eventLocation,
            'price' => $event->price,
            'type' => 'normal',
            'qr_code' => $ticketNumber,
        ]);

        return redirect()->route('dashboard.user-events')->with('msg', 'Presença confirmada no evento: ' . $event->headline . '. Ingresso gerado com sucesso!');
    }

    public function cancelRegistration($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        if ($event->price > 0) {
            $now = Carbon::now('America/Sao_Paulo');
            $eventDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $event->date_event->format('Y-m-d') . ' ' . $event->time_event,
                'America/Sao_Paulo'
            );

            $sevenBusinessDaysBefore = Carbon::now('America/Sao_Paulo');
            for ($i = 0; $i < 7; $i++) {
                $sevenBusinessDaysBefore->addDay();
                if ($sevenBusinessDaysBefore->isWeekend()) {
                    $i--; 
                }
            }

            if ($eventDateTime->gte($sevenBusinessDaysBefore)) {
                return redirect()->route('dashboard.user-events')->with('error', 'Este é um evento pago. Você pode solicitar um estorno até 7 dias úteis antes do evento. <a href="' . route('refund.request', $event->id) . '">Clique aqui para solicitar</a>.');
            }
        }

        $user->participatedEvents()->detach($id);
        Ticket::where('user_id', $user->id)->where('event_id', $id)->delete();

        return redirect()->route('dashboard.user-events')->with('msg', 'Inscrição cancelada com sucesso: ' . $event->headline);
    }

    public function showRefundRequestForm($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        $ticket = Ticket::where('user_id', $user->id)->where('event_id', $id)->first();

        if (!$ticket) {
            return redirect()->route('dashboard.user-events')->with('error', 'Você não possui um ingresso para este evento.');
        }

        return view('events.request-refunded', [
            'event' => $event,
            'ticket' => $ticket,
            'user' => $user,
        ]);
    }

    public function submitRefundRequest(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        $ticket = Ticket::where('user_id', $user->id)->where('event_id', $id)->first();

        if (!$ticket) {
            return redirect()->route('dashboard.user-events')->with('error', 'Você não possui um ingresso para este evento.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'ticket_id' => 'required|string',
            'reason' => 'required|string|max:1000',
        ]);

        

        return redirect()->route('dashboard.user-events')->with('msg', 'Solicitação de estorno enviada com sucesso! Nossa equipe entrará em contato em breve.');
    }
}