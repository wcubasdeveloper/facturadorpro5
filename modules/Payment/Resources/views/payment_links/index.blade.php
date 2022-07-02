@extends('tenant.layouts.app')

@section('content')

    <tenant-payment-links-index
        :type-user="{{json_encode(Auth::user()->type)}}"
    >
    </tenant-payment-links-index>

@endsection