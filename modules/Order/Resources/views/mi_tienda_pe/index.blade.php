@extends('tenant.layouts.app')

@section('content')

    <tenant-mitiendape-config
        :establishments="{{json_encode($establishments )}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-order-notes-index>

@endsection
