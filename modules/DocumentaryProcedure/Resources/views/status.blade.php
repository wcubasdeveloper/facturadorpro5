@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-status
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :status='@json($status)'
        :users='@json($users)'
    ></tenant-documentary-status>
@endsection
