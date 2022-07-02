@extends('tenant.layouts.app')

@section('content')

    <tenant-technical-services-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-technical-services-index>

@endsection
