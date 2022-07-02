@extends('tenant.layouts.app')

@section('content')
    <tenant-digemid-index
        type="{{ $type ?? '' }}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-digemid-index>

@endsection
