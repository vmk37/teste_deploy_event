<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TicketManualController extends Controller
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

   public function showManualForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        $paymentIntents = PaymentIntent::all([
            'limit' => 10,
        ])->data;

        $hasSucceededPayment = false;
        foreach ($paymentIntents as $paymentIntent) {
            $metadata = $paymentIntent->metadata;
            if (
                isset($metadata['event_id']) && isset($metadata['user_id']) &&
                $metadata['event_id'] === (string) $eventId &&
                $metadata['user_id'] === (string) $user->id &&
                $paymentIntent->status === 'succeeded'
            ) {
                $hasSucceededPayment = true;
                break;
            }
        }

        if (!$hasSucceededPayment) {
            return redirect()->route('dashboard')->with('error', 'Você precisa completar o pagamento antes de gerar o ingresso.');
        }

        return view('tickets.manual', ['event' => $event]);
    }

   public function createManualTicket($eventId)
{
    $event = Event::findOrFail($eventId);
    $user = Auth::user();

    if (!$event->users()->where('user_id', $user->id)->exists()) {
        $user->participatedEvents()->attach($event->id);
    }

    $ticketNumber = Str::uuid()->toString();
    $eventLocation = $event->address ? $event->address->street . ', ' . $event->address->addressNumber . ', ' . $event->address->neighborhood . ', ' . $event->address->municipality . ' - ' . $event->address->state : 'Local não especificado';
    $eventDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->date_event->format('Y-m-d') . ' ' . $event->time_event, 'America/Sao_Paulo');

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
        'transaction_id' => 'pi_' . $eventId . '_' . time(),
    ]);

    return redirect()->route('dashboard.user-events')->with('msg', 'Ingresso gerado com sucesso!');
}
}