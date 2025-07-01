@extends('layouts.main')

@section('title', $event->headline)

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endpush

<div class="container my-5">
    <div class="row g-4">
        <div id="image-container" class="col-12 col-md-6 d-flex justify-content-center align-items-center">
            <img src="{{ asset('img/events/' . $event->picture) }}" class="img-fluid rounded shadow" alt="{{ $event->headline }}">
        </div>
        <div id="info-container" class="col-12 col-md-6">
            <h1 class="mb-3">{{ $event->headline }}</h1>
            <p class="event-town mb-2">
                <ion-icon name="navigate-outline"></ion-icon>
                @if($event->address)
                    {{ $event->address->street }}, {{ $event->address->addressNumber }} - 
                    {{ $event->address->neighborhood }}, {{ $event->address->municipality }} - {{ $event->address->state }}
                @endif
            </p>
            <p class="mb-2"> 
                <ion-icon name="time-outline"></ion-icon>
                {{ $event->time_event ? date('H:i', strtotime($event->time_event)) : 'Horário não definido' }}
            </p>
            <p class="mb-2">
                <ion-icon name="people-outline"></ion-icon>
                Participantes: {{ count($event->users) }} / {{ $event->participant_limit ?? 'Sem limite' }}
            </p>
            <p class="mb-2">
                <ion-icon name="cash-outline"></ion-icon>
                Preço: {{ $event->price == 0 ? 'Gratuito' : 'R$ ' . number_format($event->price, 2, ',', '.') }}
            </p>
            <p class="event-owner mb-4">
                <ion-icon name="man-outline"></ion-icon>
                {{ $eventOrganizer['name'] }}
            </p>
            @if($event->is_expired)
                <div class="alert alert-danger" role="alert">
                    Esta partida já expirou e não aceita novas inscrições.
                </div>
            @elseif($event->participant_limit && count($event->users) >= $event->participant_limit)
                <div class="alert alert-warning event-full-msg" role="alert">
                    Esta partida já atingiu a capacidade máxima de participantes.
                </div>
            @elseif(!$isUserRegistered)
                <form action="/evento/presenca/{{$event->id}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg event-submit">
                        {{ $event->price == 0 ? 'Confirmar presença' : 'Pagar ingresso' }}
                    </button>
                </form>
            @else
                <div class="alert alert-success event-confirm-msg" role="alert">
                    Você já se inscreveu nessa partida!
                </div>
            @endif

            <div class="event-products mt-4">
                <h3 class="mb-3">O local conta com:</h3>
                <ul class="list-unstyled">
                    @foreach($event->products as $product)
                        <li class="d-flex align-items-center mb-2">
                            <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                            {{ $product->product_name }}
                        </li>
                    @endforeach
                    @if($event->custom_product)
                        <li class="d-flex align-items-center mb-2">
                            <ion-icon name="checkmark-circle-outline" class="me-2"></ion-icon>
                            {{ $event->custom_product }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-12" id="description-container">
            <h3 class="mb-3">Sobre a partida:</h3>
            <p class="event-description">{{ $event->details }}</p>
        </div>
    </div>

</div>

@endsection