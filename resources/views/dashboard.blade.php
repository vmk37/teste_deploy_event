@extends('layouts.main')

@section('title', 'Dash Board')

@section('content')

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Meus eventos</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if(count($events) > 0)
    @else
    <p>Você ainda não tem eventos, <a href="/evento/criacao"> Criar evento</a></p>
    @endif
</div>


@endsection
