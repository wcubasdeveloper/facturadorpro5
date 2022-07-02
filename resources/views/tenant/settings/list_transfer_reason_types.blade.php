@extends('tenant.layouts.app')

@section('content')
    <tenant-transfer-reason-types-index :type-user="{{json_encode(Auth::user()->type)}}"></tenant-transfer-reason-types-index>
@endsection
