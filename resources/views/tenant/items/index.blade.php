@extends('tenant.layouts.app')

@section('content')
    <tenant-items-index
        type="{{ $type ?? '' }}"
        :configuration="{{\App\Models\Tenant\Configuration::first()->toJson()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-items-index>
@endsection
