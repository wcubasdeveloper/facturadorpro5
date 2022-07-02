@extends('tenant.layouts.app')

@section('content')

    <tenant-report-general-items-index
        @if(isset($typereport))
        :default-type="'{{$typereport}}'"
        @endif
        @if(isset($configuration))
        :configuration="{{$configuration}}"
        @endif
        
        @if(isset($apply_conversion_to_pen))
            :apply-conversion-to-pen="{{ json_encode($apply_conversion_to_pen) }}"
        @endif
        
    ></tenant-report-general-items-index>


@endsection
