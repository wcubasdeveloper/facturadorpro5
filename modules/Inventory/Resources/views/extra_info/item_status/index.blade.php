@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-status
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-status>

@endsection
