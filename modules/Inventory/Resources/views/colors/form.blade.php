@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-devolutions-form
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        ></tenant-inventory-devolutions-form>

@endsection
