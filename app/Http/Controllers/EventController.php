<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Address;
use App\Models\Product;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $research = request('research');
        $now = Carbon::now('America/Sao_Paulo');

        if ($research) {
            $events = Event::where('headline', 'like', '%' . $research . '%')
                           ->where('is_expired', false)
                           ->get();
        } else {
            $events = Event::where('is_expired', false)->get();
        }

        $events = $events->filter(function ($event) use ($now) {
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

        return view('welcome', ['events' => $events, 'research' => $research]);
    }

    public function create()
    {
        $availableProducts = Product::all();
        return view('events.create', ['availableProducts' => $availableProducts]);
    }

    public function store(Request $request)
    {
        $event = new Event;
        $event->headline = $request->headline;
        $event->date_event = $request->date_event;
        $event->price = $request->price ?? 0.00;
        $event->details = $request->details;
        $event->time_event = $request->time_event;
        $event->participant_limit = $request->participant_limit;

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $picturePath = public_path('img/events');
            if (!file_exists($picturePath)) {
                mkdir($picturePath, 0755, true);
            }
            $pictureName = md5($request->picture->getClientOriginalName() . strtotime("now")) . "." . $request->picture->extension();
            $request->picture->move($picturePath, $pictureName);
            $event->picture = $pictureName;
        }

        $event->user_id = Auth::id();
        $event->save();

        $addressData = $request->only(['cep', 'street', 'addressNumber', 'neighborhood', 'municipality', 'state']);
        $addressData['event_id'] = $event->id;
        Address::create($addressData);

        if ($request->products || $request->custom_product) {
            $products = collect($request->products ?? []);
            if ($request->custom_product) {
                $products->push($request->custom_product);
            }

            foreach ($products as $productName) {
                Product::create([
                    'event_id' => $event->id,
                    'product_name' => $productName
                ]);
            }
        }

        return redirect('/');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        if (Auth::user()->id != $event->user->id) {
            return redirect()->route('dashboard.created-events')->with('error', 'Você não tem permissão para editar este evento.');
        }

        if ($event->is_expired) {
            return redirect()->route('dashboard.created-events')->with('error', 'Este evento já expirou e não pode ser editado.');
        }

        $availableProducts = Product::all();
        $eventProducts = $event->products;

        return view('events.edit', [
            'event' => $event,
            'availableProducts' => $availableProducts,
            'eventProducts' => $eventProducts
        ]);
    }

    public function update(Request $request)
    {
        $event = Event::findOrFail($request->id);

        if (Auth::user()->id != $event->user->id) {
            return redirect()->route('dashboard.created-events')->with('error', 'Você não tem permissão para editar este evento.');
        }

        if ($event->is_expired) {
            return redirect()->route('dashboard.created-events')->with('error', 'Este evento já expirou e não pode ser editado.');
        }

        $event->update($request->only(['headline', 'date_event', 'time_event', 'price', 'details', 'participant_limit']));

        $event->products()->delete();

        if ($request->products || $request->custom_product) {
            $products = collect($request->products ?? []);
            if ($request->custom_product) {
                $products->push($request->custom_product);
            }

            foreach ($products as $productName) {
                Product::create([
                    'event_id' => $event->id,
                    'product_name' => $productName
                ]);
            }
        }

        return redirect()->route('dashboard.created-events')->with('msg', 'Evento editado com sucesso!');
    }

    public function destroy($id)
{
    $event = Event::find($id);

    if (!$event) {
        return redirect()->route('dashboard.created-events')->with('error', 'Evento não encontrado.');
    }

    if ($event->users()->count() > 0) {
        return redirect()->route('dashboard.created-events')->with('error', 'Este evento possui inscritos e não pode ser excluído. Entre em contato com o suporte: suporte@eventconnect.com');
    }

    if ($event->picture) {
        $imagePath = public_path('img/events/' . $event->picture);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $event->address()->delete();
    $event->products()->delete();
    $event->delete();

    return redirect()->route('dashboard.created-events')->with('msg', 'Evento e seus dados associados excluídos com sucesso!');
}
}