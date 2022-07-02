@extends('tenant.layouts.app')

@section('content')

    <tenant-full-suscription-client-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :listtype="'parent'"
    ></tenant-full-suscription-client-index>

@endsection
