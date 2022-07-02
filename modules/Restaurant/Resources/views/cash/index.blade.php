@extends('tenant.layouts.app')

@section('content')

    <tenant-restaurant-cash-index :type-user="{{json_encode(Auth::user()->type)}}"  ></tenant-restaurant-cash-index>

@endsection
