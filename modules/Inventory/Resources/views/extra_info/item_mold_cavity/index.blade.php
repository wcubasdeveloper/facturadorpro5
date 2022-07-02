@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-mold-cavities
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-inventory-mold-cavities>

@endsection
