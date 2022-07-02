@extends('tenant.layouts.app')

@section('content')
    <tenant-report-items-index
        @if(isset($typereport))
        :default-type="'{{$typereport}}'"
        @endif
        @if(isset($configuration))
        :configuration="{{$configuration}}"
        @endif
    ></tenant-report-items-index>


@endsection
