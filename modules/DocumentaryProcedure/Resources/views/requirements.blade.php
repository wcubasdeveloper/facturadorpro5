@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-requirements
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :requirements='@json($requirements_list)'
    ></tenant-documentary-requirements>
@endsection
