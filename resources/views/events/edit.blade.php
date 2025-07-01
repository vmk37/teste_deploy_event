@extends('layouts.main')

@section('title', 'Editando ' .$event->headline)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endpush

@section('content')
    <div id="event-create-container" class="event-create-container col-md-6 offset-md-3">
        <h1>Editando: {{$event->headline}}</h1>
        <form action="/evento/modificar/{{$event->id}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Imagem da partida -->
            <div class="form-group">
                <label for="picture" class="mb-2">Imagem de divulgação do confronto:</label>
                <input type="file" id="picture" name="picture" class="form-control-file">
                <img 
                    src="/img/events/{{$event->picture}}" 
                    alt="{{$event->headline}}" 
                    class="img-preview"
                    >
            </div>

            <!-- Nome da partida -->
            <div class="form-group mb-3">
                <label for="headline" class="mb-2">Nome da Partida:</label>
                <input type="text" class="form-control" id="headline" name="headline" placeholder="TimeA vs TimeB" value="{{$event->headline}}">
            </div>

            <!-- Preço do Ingresso -->
            <div class="form-group mb-3">
                <label for="price" class="mb-2">Preço do Ingresso (R$):</label>
                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ $event->price }}" required>
            </div>

            <!-- Número de participantes -->
            <div class="form-group mb-3">
                <label for="participant_limit" class="mb-2">Limite de participantes:</label>
                <input type="number" class="form-control" id="participant_limit" name="participant_limit" value="{{ $event->participant_limit }}" placeholder="Defina o limite de participantes (opcional)">
            </div>

            <!-- Data do jogo -->
            <div class="form-group mb-3">
                <label for="date_event" class="mb-2">Data:</label>
                <input type="date" class="form-control" id="date_event" name="date_event" value="{{ date('Y-m-d', strtotime($event->date_event)) }}" min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
            </div>

            <!-- Horário do jogo -->
            <div class="form-group mb-3">
                <label for="time_event" class="mb-2">Horário de início:</label>
                <input type="time" class="form-control" id="time_event" name="time_event" value="{{ $event->time_event }}">
            </div>

            <!--Endereço -->
            @if($event->address)
                <div class="form-group mb-3">
                    <label for="cep" class="mb-2">CEP:</label>
                    <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP" value="{{ $event->address->cep }}" onblur="buscarCEP()">
                </div>

                <div class="form-group mb-3">
                    <label for="addressNumber" class="mb-2">Número:</label>
                    <input type="text" class="form-control" id="addressNumber" name="addressNumber" placeholder="Número" value="{{ $event->address->addressNumber }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="street" class="mb-2">Logradouro:</label>
                    <input type="text" class="form-control" id="street" name="street" placeholder="Rua/Avenida" value="{{ $event->address->street }}" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="neighborhood" class="mb-2">Bairro:</label>
                    <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro" value="{{ $event->address->neighborhood }}" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="municipality" class="mb-2">Município:</label>
                    <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Município" value="{{ $event->address->municipality }}" readonly>
                </div>

                <div class="form-group mb-3">
                    <label for="state" class="mb-2">Estado:</label>
                    <input type="text" class="form-control" id="state" name="state" placeholder="Estado" value="{{ $event->address->state }}" readonly>
                </div>
            @endif

            <!-- Descrição da partida -->
            <div class="form-group mb-3">
                <label for="details" class="mb-2">Detalhes da Partida:</label>
            <textarea name="details" id="details" class="form-control" placeholder="Descreva o formato ou outros detalhes importante">{{ $event->details }}</textarea>
            </div>

            <!-- Infraestrutura local -->
            <div class="form-group mb-3">
                <label for="products">O local da partida contém:</label>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Arquibancada" {{ in_array('Arquibancada', $event->products->pluck('product_name')->toArray()) ? 'checked' : '' }}> Arquibancada
                </div>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Banheiros" {{ in_array('Banheiros', $event->products->pluck('product_name')->toArray()) ? 'checked' : '' }}> Banheiros
                </div>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Food Truck" {{ in_array('Food Truck', $event->products->pluck('product_name')->toArray()) ? 'checked' : '' }}> Food Truck
                </div>

                <div class="form-group">
                    <label id="outro">Outros</label>
                    <input type="text" for="outro" name="custom_product" placeholder="Digite aqui" value="{{ $event->products->where('product_name', '!=', 'Arquibancada')->where('product_name', '!=', 'Banheiros')->where('product_name', '!=', 'Food Truck')->pluck('product_name')->first() }}">
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Editar Partida ">
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/create.js') }}"></script>
    <script src="{{ asset('js/cepCreateEdit.js') }}"></script> 
@endpush