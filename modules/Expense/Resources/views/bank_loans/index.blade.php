@extends('tenant.layouts.app')

@section('content')

    <tenant-bankloans-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-bankloans-index>

@endsection
