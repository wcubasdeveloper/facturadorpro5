@extends('tenant.layouts.app')

@section('content')

    <tenant-full-suscription-index-payment-receipt
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :soap-company="{{ json_encode($soap_company) }}">
    </tenant-full-suscription-index-payment-receipt>




@endsection
