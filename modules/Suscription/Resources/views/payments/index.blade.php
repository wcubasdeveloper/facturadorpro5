@extends('tenant.layouts.app')

@section('content')

<tenant-suscription-payments-index
    :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    :date="'{{Carbon\Carbon::now()->format('Y-m-d')}}'"
></tenant-suscription-payments-index>

@endsection
