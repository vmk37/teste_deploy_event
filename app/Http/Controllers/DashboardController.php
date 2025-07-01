<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Ticket;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Event;

class DashboardController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function dashboard()
    {
        $data = $this->getDashboardData();

        return view('events.dashboard', [
            'createdEvents' => $data['createdEvents'],
            'participatedEvents' => $data['participatedEvents'],
            'historicalEvents' => $data['historicalEvents'],
            'tickets' => $data['tickets'],
            'pendingPayments' => $data['pendingPayments']
        ]);
    }

    public function userEvents()
    {
        $data = $this->getDashboardData();

        return view('events.user-events', [
            'participatedEvents' => $data['participatedEvents'],
            'historicalEvents' => $data['historicalEvents'],
            'tickets' => $data['tickets'],
            'pendingPayments' => $data['pendingPayments']
        ]);
    }

    public function createdEvents()
    {
        $data = $this->getDashboardData();

        return view('events.created-events', [
            'createdEvents' => $data['createdEvents']
        ]);
    }

    private function getDashboardData()
    {
        $user = Auth::user();
        $now = Carbon::now('America/Sao_Paulo');

        $createdEvents = $this->getFilteredEvents($user->events()->where('is_expired', false)->get(), $now);
        $participatedEvents = $this->getFilteredEvents($user->participatedEvents()->where('is_expired', false)->get(), $now);
        $historicalEvents = $user->participatedEvents()->where('is_expired', true)->get();
        $tickets = Ticket::where('user_id', $user->id)->get();
        $pendingPayments = $this->getPendingPayments($user);

        return [
            'createdEvents' => $createdEvents,
            'participatedEvents' => $participatedEvents,
            'historicalEvents' => $historicalEvents,
            'tickets' => $tickets,
            'pendingPayments' => $pendingPayments
        ];
    }

    private function getFilteredEvents($events, $now)
    {
        return $events->filter(function ($event) use ($now) {
            $eventDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $event->date_event->format('Y-m-d') . ' ' . $event->time_event,
                'America/Sao_Paulo'
            );

            if ($eventDateTime->lte($now) && !$event->is_expired) {
                $event->update(['is_expired' => true]);
                return false;
            }

            return true;
        });
    }

    private function getPendingPayments($user)
    {
        $pendingPayments = [];
        $thirtyDaysAgo = Carbon::now()->subDays(30)->timestamp;

        $paymentIntents = PaymentIntent::all([
            'limit' => 100,
            'created' => ['gte' => $thirtyDaysAgo],
        ])->data;

        foreach ($paymentIntents as $paymentIntent) {
            $metadata = $paymentIntent->metadata;

            if (
                isset($metadata['event_id']) &&
                isset($metadata['user_id']) &&
                $metadata['user_id'] === (string) $user->id &&
                $paymentIntent->status === 'succeeded'
            ) {
                $eventId = $metadata['event_id'];
                $event = Event::find($eventId);

                if ($event && !$event->tickets()->where('user_id', $user->id)->exists()) {
                    $pendingPayments[$eventId] = $event;
                }
            }
        }

        return $pendingPayments;
    }
}
