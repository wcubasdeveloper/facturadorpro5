@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-color-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-color-index>

@endsection
