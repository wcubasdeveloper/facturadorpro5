@extends('tenant.layouts.web')

@section('content')

    <tenant-public-payment-links-index
        :payment_link="{{json_encode($payment_link)}}"
        :company="{{json_encode($company)}}"
        :payment_configuration="{{json_encode($payment_configuration)}}"
        :total="{{json_encode($total)}}"
        :apply_conversion="{{json_encode($apply_conversion)}}"
    >
    </tenant-public-payment-links-index>

@endsection

{{-- mercadopago --}}
@if ($payment_link['payment_link_type_id'] == '02')
    
    @section('content-mercadopago')

        <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

        @inject('mercadoPago', 'Modules\MercadoPago\Services\MercadoPagoService')
        @php
            $public_key = $mercadoPago->getPublicKey();
        @endphp

        <script>
            window.token_mercado_pago = '{{$public_key}}';
        </script>

    @endsection

@endif
{{-- mercadopago --}}
