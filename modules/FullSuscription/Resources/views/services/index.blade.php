@extends('tenant.layouts.app')

@section('content')

    <tenant-items-index
        type="{{ 'ZZ' }}"
        :configuration="{{\App\Models\Tenant\Configuration::first()->toJson()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-items-index>

    <!--
    <tenant-suscription-service-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-suscription-service-index>

    -->

@endsection
