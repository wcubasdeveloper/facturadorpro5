@extends('tenant.layouts.app')

@section('content')

    <tenant-report-items-extra-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    >

    </tenant-report-items-extra-index>

@endsection
