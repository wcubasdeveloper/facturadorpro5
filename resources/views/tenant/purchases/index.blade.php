@extends('tenant.layouts.app')

@section('content')

    <tenant-purchases-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-purchases-index>

@endsection
