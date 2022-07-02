@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-package-measurements
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-package-measurements>

@endsection
