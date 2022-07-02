@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-size-property
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-size-property>

@endsection
