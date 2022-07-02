@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-units-business
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-units-business>

@endsection
