@extends('layouts.main')

@section('title', 'Pagamento - ' . $event->headline)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3 payment-container">
                <h1>Pagamento - {{ $event->headline }}</h1>
                <p>Valor: R$ {{ number_format($event->price, 2, ',', '.') }}</p>

                @if ($event->price > 0)
                   <form id="payment-form" action="{{ route('payment.process', $event->id) }}" method="POST" data-stripe-key="{{ env('STRIPE_KEY') }}">
    @csrf
    <div class="form-group">
        <label for="payment_method">Método de Pagamento:</label>
        <select name="payment_method" id="payment_method" class="form-control" required>
            <option value="card">Cartão</option>
            <option value="boleto">Boleto</option>
        </select>
    </div>
    <div id="boleto_fields" style="display: none;">
        <div class="form-group">
            <label for="postal_code">CEP:</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control" data-required="true" pattern="\d{5}-\d{3}">
        </div>
        <div class="form-group">
            <label for="address_number">Número:</label>
            <input type="text" name="address_number" id="address_number" class="form-control" data-required="true">
        </div>
        <div class="form-group">
            <label for="street">Rua:</label>
            <input type="text" name="street" id="street" class="form-control" data-required="true">
        </div>
        <div class="form-group">
            <label for="city">Cidade:</label>
            <input type="text" name="city" id="city" class="form-control" data-required="true">
        </div>
        <div class="form-group">
            <label for="state">Estado:</label>
            <input type="text" name="state" id="state" class="form-control" data-required="true" maxlength="2">
        </div>
    </div>
    <div id="card-element" style="display: block;"></div>
    <div id="payment-message" class="text-danger"></div>
    <button type="submit" class="btn btn-primary">Pagar</button>
</form>
                @else
                    <p>Este evento é gratuito! Você pode se inscrever diretamente.</p>
                    <a href="{{ route('event.join', $event->id) }}" class="btn btn-success">Inscrever-se</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/payment.js') }}"></script>
    <script src="{{ asset('js/boletoFields.js') }}"></script>
    <script src="{{ asset('js/cepPayment.js') }}"></script>
@endpush