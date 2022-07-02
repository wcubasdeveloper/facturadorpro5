@extends('tenant.layouts.app')

@section('content')

    <tenant-index-payment-receipt
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :soap-company="{{ json_encode($soap_company) }}">
        </tenant-index-payment-receipt>




@endsection
