@extends('tenant.layouts.app')

@section('content')
    <tenant-report-guide-index
        :configuration='{{$configuration}}'
    ></tenant-report-guide-index>
@endsection
