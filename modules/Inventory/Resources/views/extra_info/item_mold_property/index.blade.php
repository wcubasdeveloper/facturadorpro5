@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-mold-property
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-mold-property>

@endsection
