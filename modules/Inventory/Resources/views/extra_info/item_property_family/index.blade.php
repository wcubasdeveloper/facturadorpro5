@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-product-family
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-product-family>

@endsection
