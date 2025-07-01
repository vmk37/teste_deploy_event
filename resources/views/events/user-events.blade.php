@extends('layouts.main')

@section('title', 'Meus Eventos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1 dashboard-container">
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!} 
                    </div>
                @endif

                {{-- Jogos Inscritos --}}
                <div class="col-md-10 offset-md-1 dashboard-title-container">
                    <h1>Jogos Inscritos</h1>
                </div>
                <div class="col-md-10 offset-md-1 dashboard-events-container">
                    @if(is_countable($participatedEvents) && count($participatedEvents) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Horário</th>
                                        <th scope="col">Participantes</th>
                                        <th scope="col">Configurações da Partida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participatedEvents as $event)
                                        <tr>
                                            <td scope="row">{{ $loop->index + 1 }}</td>
                                            <td><a href="/evento/{{ $event->id }}">{{ $event->headline }}</a></td>
                                            <td>{{ date('d/m/Y', strtotime($event->date_event)) }}</td>
                                            <td>{{ $event->time_event }}</td>
                                            <td>{{ count($event->users) }}</td>
                                            <td>
                                                <form action="/evento/cancelar/{{ $event->id }}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button type="submit" class="btn btn-danger delete-btn w-80 ">
                                                        <ion-icon name="trash-outline"></ion-icon> Sair do jogo
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Você ainda não está participando de nenhum evento, <a href="/">Veja todos os eventos</a></p>
                    @endif
                </div>

                <!-- Histórico de Eventos -->
                <div class="col-md-10 offset-md-1 dashboard-title-container">
                    <h1>Histórico de Partidas</h1>
                </div>
                <div class="col-md-10 offset-md-1 dashboard-events-container">
                    @if(is_countable($historicalEvents) && count($historicalEvents) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Horário</th>
                                        <th scope="col">Participantes</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historicalEvents as $event)
                                        <tr>
                                            <td scope="row">{{ $loop->index + 1 }}</td>
                                            <td><a href="/evento/{{ $event->id }}">{{ $event->headline }}</a></td>
                                            <td>{{ date('d/m/Y', strtotime($event->date_event)) }}</td>
                                            <td>{{ $event->time_event }}</td>
                                            <td>{{ count($event->users) }}</td>
                                            <td>Finalizado</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Você ainda não participou de nenhum evento.</p>
                    @endif
                </div>

                <!-- Meus Ingressos -->
                <div class="col-md-10 offset-md-1 dashboard-title-container">
                    <h1>Meus Ingressos</h1>
                </div>
                <div class="col-md-10 offset-md-1 dashboard-events-container">
                    @if(is_countable($tickets) && count($tickets) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Evento</th>
                                        <th scope="col">Número do Ingresso</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Configurações do Ingresso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td scope="row">{{ $loop->index + 1 }}</td>
                                            <td>{{ $ticket->event_headline }}</td>
                                            <td>{{ $ticket->ticket_number }}</td>
                                            <td>{{ ucfirst($ticket->type) }}</td>
                                            <td>
                                                <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-info">
                                                    <ion-icon name="ticket-outline"></ion-icon> Ver Ingresso
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Você ainda não possui ingressos.</p>
                    @endif
                </div>

                <!-- Pagamentos Confirmados  Ingresso -->
                @if (!empty($pendingPayments))
                    <div class="col-md-10 offset-md-1 dashboard-title-container">
                        <h1>Acesse o link abaixa para gerar o seu ingresso :</h1>
                    </div>
                    <div class="col-md-10 offset-md-1 dashboard-events-container">
                        @foreach ($pendingPayments as $event)
                            <div class="alert alert-warning">
                                <a href="{{ route('ticket.manual.form', $event->id) }}" class="btn btn-primary">Ver Ingresso</a>
                            </div>
                        @endforeach
                    </div>
  @endif
            </div>
        </div>
    </div>
@endsection