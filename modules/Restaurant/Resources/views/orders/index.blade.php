@extends('tenant.layouts.app')

@section('content')
    <tenant-restaurant-orders-index :user="{{ json_encode(auth()->user()) }}"></tenant-restaurant-orders-index>
@endsection
