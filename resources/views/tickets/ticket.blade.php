@extends('layouts.main')

@section('title', 'Ingresso: ' . $ticket->event_headline)

@section('content')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endpush    

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('js/qrcode.js') }}"></script>
@endpush

<div class="container mt-5">
    <div class="col-md-10 offset-md-1 ticket-container">
        <h1 class="text-center">{{ $ticket->event_headline }}</h1>
        <div class="row">
            <div class="col-md-6 ticket-details">
                <p><strong>ID do Ingresso:</strong> {{ $ticket->ticket_number }}</p>
                <p><strong>Categoria:</strong> {{ ucfirst($ticket->type) }}</p>
                <p><strong>Valor Pago:</strong> R$ {{ number_format($ticket->price, 2, ',', '.') }}</p>
                <h3 class="mt-4">Informações do Participante</h3>
                <p><strong>Nome Completo:</strong> {{ $ticket->user_name }}</p>
                <p><strong>CPF:</strong> {{ $ticket->user_cpf }}</p>
                <h3 class="mt-4">Dados do Evento</h3>
                <p><strong>Data e Horário:</strong> {{ $ticket->event_date->format('d/m/Y H:i') }}</p>
                <p><strong>Local:</strong> {{ $ticket->event_location }}</p>
            </div>
            <div class="col-md-6 qrcode-container">
                <h3>Código QR</h3>
                <div id="qrcode" class="d-flex justify-content-center"></div>
                <p class="text-center">Escaneie o código QR no dia do evento para validação.</p>
            </div>
        </div>
    </div>

    <div class="button-container">
        <button onclick="window.print()" class="btn btn-secondary btn-lg">Imprimir Ingresso</button>
        <a href="{{ route('dashboard.user-events') }}" class="btn btn-primary btn-lg">Voltar aos Meus Eventos</a>
    </div>
</div>

@endsection
