@extends('layouts.main')

@section('title', 'Jogos Criados')

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
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Jogos Criados --}}
                <div class="col-md-10 offset-md-1 dashboard-title-container">
                    <h1>Jogos Criados</h1>
                </div>
                <div class="col-md-10 offset-md-1 dashboard-events-container">
                    @if(count($createdEvents) > 0)
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
                                    @foreach($createdEvents as $event)
                                        <tr>
                                            <td scope="row">{{ $loop->index + 1 }}</td>
                                            <td><a href="/evento/{{ $event->id }}">{{ $event->headline }}</a></td>
                                            <td>{{ date('d/m/Y', strtotime($event->date_event)) }}</td>
                                            <td>{{ $event->time_event }}</td>
                                            <td>{{ count($event->users) }}</td>
                                            <td>
                                                <a href="/evento/editar/{{ $event->id }}" class="btn btn-info edit-btn">
                                                    <ion-icon name="create-outline"></ion-icon> Editar
                                                </a>
                                                <form action="/evento/{{ $event->id }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-btn">
                                                        <ion-icon name="trash-outline"></ion-icon> Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Você ainda não tem jogos criados, <a href="/evento/criacao">Criar jogo</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection