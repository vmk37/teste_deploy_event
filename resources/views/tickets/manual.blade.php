@extends('layouts.main')

@section('title', 'Confirmação do Ingresso')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3 payment-container">
                <h1><strong>Partida:</strong> {{ $event->headline }}</h1>

                <p class="text-center">O pagamento do seu boleto foi confirmado com sucesso!</p>

                <p class="text-center">Para finalizar, clique no botão abaixo e confirme a geração do seu ingresso.</p>

                <form action="{{ route('ticket.manual', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Gerar Ingresso</button>
                </form>

                <a href="{{ route('dashboard.user-events') }}" class="btn btn-secondary">Voltar ao Meus Eventos</a>
            </div>
        </div>
    </div>
@endsection
