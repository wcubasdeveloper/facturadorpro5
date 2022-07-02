@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-create
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-dispatches-create>
@endsection
