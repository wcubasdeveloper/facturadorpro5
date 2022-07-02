@extends('tenant.layouts.app')

@section('content')

    {{-- @todo - error en archivo, es reemplazado por \resources\views\modules\report\general_items\index.blade.php revisar --}}

    <tenant-report-general-items-index
        @if(isset($typeresource))
            :typeresource="'{!! $typeresource !!}'"
            @endif
        @if(isset($typereport))
            :typeresource="'{!! $typereport !!}'"
            @endif
    ></tenant-report-general-items-index>

@endsection
