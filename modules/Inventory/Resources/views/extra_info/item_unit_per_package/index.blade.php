@extends('tenant.layouts.app')

@section('content')
    <tenant-inventory-item-units-per-package-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-units-per-package-index>

@endsection
