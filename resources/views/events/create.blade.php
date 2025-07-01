@extends('layouts.main')

@section('title', 'Criação de Partidas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/create.js') }}"></script>
@endpush

@section('content')
    <div id="event-create-container" class="event-create-container col-md-6 offset-md-3">
        <h1>Crie o seu jogo</h1>
        <form action="/eventos" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Imagem do Jogo -->
            <div class="form-group">
                <label for="picture" class="mb-2">Imagem de divulgação do confronto:</label>
                <input type="file" id="picture" name="picture" class="form-control-file" onchange="previewImage(event)" required>
                <img class="img-event" id="image-preview" src="#" alt="Pré-visualização da imagem">
            </div>

            <!-- Nome do Jogo -->
            <div class="form-group mb-3">
                <label for="headline" class="mb-2">Nome da Partida:</label>
                <input type="text" class="form-control" id="headline" name="headline" placeholder="TimeA vs TimeB" required>
            </div>

            <!-- Preço do Ingresso -->
            <div class="form-group mb-3">
                <label for="price" class="mb-2">Preço do Ingresso (R$):</label>
                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" placeholder="0,00 (gratuito)" required>
            </div>

            <!-- Número de participantes -->
            <div class="form-group mb-3">
                <label for="participant_limit" class="mb-2">Limite de participantes:</label>
                <input type="number" class="form-control" id="participant_limit" name="participant_limit" placeholder="Defina o limite de participantes (opcional)">
            </div>

            <!-- Data do jogo -->
            <div class="form-group">
                <label for="date_event" class="mb-2">Data:</label>
                <input type="date" class="form-control" id="date_event" name="date_event" min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
            </div>

            <!-- Horário do jogo -->
            <div class="form-group mb-3">
                <label for="time_event" class="mb-2">Horário de início:</label>
                <input type="time" class="form-control" id="time_event" name="time_event" required>
             </div>

            <!-- endereço -->
            <div class="form-group mb-3">
                <label for="cep" class="mb-2">CEP:</label>
                <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP" required onblur="buscarCEP()">
            </div>
            
            <div class="form-group mb-3">
                <label for="addressNumber" class="mb-2">Número:</label>
                <input type="text" class="form-control" id="addressNumber" name="addressNumber" placeholder="Número" required>
            </div>
            <div class="form-group mb-3">
                <label for="street" class="mb-2">Logradouro:</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="Rua/Avenida" required>
            </div>
            <div class="form-group mb-3">
                <label for="neighborhood" class="mb-2">Bairro:</label>
                <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Bairro" required>
            </div>
            <div class="form-group mb-3">
                <label for="municipality" class="mb-2">Município:</label>
                <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Município" readonly>
            </div>
            <div class="form-group mb-3">
                <label for="state" class="mb-2">Estado:</label>
                <input type="text" class="form-control" id="state" name="state" placeholder="Estado" readonly>
            </div>

            <!-- Descrição do Jogo -->
            <div class="form-group mb-3">
                <label for="details" class="mb-2">Detalhes da Partida:</label>
                <textarea name="details" id="details" class="form-control" placeholder="Descreva o formato ou outros detalhes importantes" required></textarea>
            </div>

            <!-- Infraestrutura local -->
            <div class="form-group mb-3">
                <label for="products">O local da partida contém:</label>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Arquibancada"> Arquibancada
                </div>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Banheiros"> Banheiros
                </div>
                <div class="form-group">
                    <input type="checkbox" name="products[]" value="Food Truck"> Food Truck
                </div>

                <div class="form-group">
                    <label id="outro">Outros</label>
                    <input type="text" for="outro" name="custom_product" placeholder="Digite aqui">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Criar Partida">
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/create.js') }}"></script>
    <script src="{{ asset('js/cepCreateEdit.js') }}"></script> 
@endpush