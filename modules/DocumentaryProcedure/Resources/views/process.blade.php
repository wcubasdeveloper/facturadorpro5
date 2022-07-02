@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-processes
        :processes='@json($processes)'
        :stages='@json($stages)'
        :requirements='@json($requirements)'
    ></tenant-documentary-processes>
@endsection
