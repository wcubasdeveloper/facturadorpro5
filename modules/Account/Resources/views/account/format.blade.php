@extends('tenant.layouts.app')

@section('content')

    <tenant-account-format
        :currencies="{{json_encode($currencies)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-account-format>

@endsection
