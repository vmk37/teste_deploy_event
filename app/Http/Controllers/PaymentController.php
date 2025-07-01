<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->price == 0) {
            return redirect()->route('event.join', $event->id);
        }

        return view('payment.form', ['event' => $event]);
    }

    public function process(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        if ($event->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('event.show', $event->id)->with('error', 'Você já está inscrito neste evento.');
        }

        $now = Carbon::now('America/Sao_Paulo');
        $eventDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $event->date_event->format('Y-m-d') . ' ' . $event->time_event,
            'America/Sao_Paulo'
        );

        if ($event->is_expired || $eventDateTime->lte($now)) {
            return redirect()->route('event.show', $event->id)->with('error', 'Este evento já expirou.');
        }

        if ($event->participant_limit && $event->users()->count() >= $event->participant_limit) {
            return redirect()->route('event.show', $event->id)->with('error', 'O limite de participantes foi atingido.');
        }

        $paymentMethod = $request->input('payment_method');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            if ($paymentMethod === 'card') {
                $amountInCents = (int) round($event->price * 100);
                $charge = Charge::create([
                    'amount' => $amountInCents,
                    'currency' => 'brl',
                    'source' => $request->input('token'),
                    'description' => 'Pagamento para evento ' . $event->id,
                ]);

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
                    'transaction_id' => $charge->id,
                ]);

                $user->participatedEvents()->attach($event->id);

                return redirect()->route('dashboard.user-events')->with('msg', 'Pagamento aprovado! Ingresso gerado para: ' . $event->headline);
            } elseif ($paymentMethod === 'boleto') {
                $request->validate([
                    'street' => 'required',
                    'address_number' => 'required',
                    'city' => 'required',
                    'state' => 'required|size:2',
                    'postal_code' => 'required|regex:/^\d{5}-\d{3}$/',
                ]);

                $cleanCpf = preg_replace('/[^0-9]/', '', $user->cpf ?? '');
                if (!$cleanCpf || strlen($cleanCpf) !== 11) {
                    return redirect()->route('payment.show', $event->id)->with('error', 'Por favor, forneça um CPF válido no seu perfil.');
                }

                $address = [
                    'line1' => $request->input('street') . ', ' . $request->input('address_number'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'postal_code' => $request->input('postal_code'),
                    'country' => 'BR',
                ];

                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) round($event->price * 100),
                    'currency' => 'brl',
                    'payment_method_types' => ['boleto'],
                    'payment_method_data' => [
                        'type' => 'boleto',
                        'boleto' => [
                            'tax_id' => $cleanCpf,
                        ],
                        'billing_details' => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'address' => $address,
                        ],
                    ],
                    'metadata' => [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                    ],
                ]);

                $paymentIntent->confirm();

                return view('payment.success', [
                    'paymentIntent' => $paymentIntent,
                    'event' => $event,
                ]);
            } else {
                return redirect()->route('payment.show', $event->id)->with('error', 'Método de pagamento inválido.');
            }
        } catch (\Exception $e) {
            return redirect()->route('payment.show', $event->id)->with('error', 'Erro ao processar o pagamento: ' . $e->getMessage());
        }
    }
}