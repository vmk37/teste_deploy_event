@extends('layouts.main')

@section('title', 'Pagamento Iniciado')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3 payment-container">
                <h1>Pagamento Iniciado - {{ $event->headline }}</h1>
                <p>Valor: R$ {{ number_format($event->price, 2, ',', '.') }}</p>

                @if($paymentIntent->next_action && isset($paymentIntent->next_action->boleto_display_details->pdf))
                    <p>O boleto foi gerado com sucesso!</p>
                    <p><strong>Link do Boleto:</strong> <a href="{{ $paymentIntent->next_action->boleto_display_details->pdf }}" target="_blank">Baixar Boleto</a></p>
                    <p>Após aprovação do pagamento, a geração do ingresso em Meus Eventos pode levar até 1 hora </p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Voltar ao Dashboard</a>
                @else
                    <div id="boleto-loading">
                        <p><strong>Gerando o boleto...</strong></p>
                        <p>Por favor, aguarde. Isso pode levar alguns segundos.</p>
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Carregando...</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if(!$paymentIntent->next_action || !isset($paymentIntent->next_action->boleto_display_details->pdf))
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 5000);
        </script>
    @endif
@endpush