@extends('tenant.layouts.app')

@section('content')

    <tenant-restaurant-cash-filter-pos :type-user="{{json_encode(Auth::user()->type)}}"  ></tenant-restaurant-cash-filter-pos>

@endsection
