@extends('tenant.layouts.app')

@section('content')

    <tenant-bankloans-form :id="{{ json_encode($id) }}"></tenant-bankloans-form>

@endsection
