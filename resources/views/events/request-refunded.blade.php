@extends('layouts.main')

@section('title', 'Solicitação de Estorno')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/request-refunded.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="title-container">
                    <h1>Solicitação de Estorno</h1>
                </div>

                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ kob('msg') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="col-md-8 offset-md-2">
                    <p class="paragrafo">Preencha o formulário abaixo para solicitar o estorno do ingresso para o evento <strong>{{ $event->headline }}</strong>. Após o envio, nossa equipe entrará em contato pelo e-mail <strong>suporte@eventconnect.com</strong>.</p>

                    <form action="{{ route('refund.submit', $event->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="full_name">Nome Completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ticket_id">ID do Ingresso</label>
                            <input type="text" class="form-control" id="ticket_id" name="ticket_id" value="{{ $ticket->ticket_number }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="reason">Motivo da Solicitação</label>
                            <textarea class="form-control" id="reason" name="reason" rows="5" required placeholder="Descreva o motivo da solicitação de estorno"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-request">Enviar Solicitação</button>
                        <a href="{{ route('dashboard.user-events') }}" class="btn btn-secondary-request">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection