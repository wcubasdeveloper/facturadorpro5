@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-statistic
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-documentary-statistic>
@endsection
