@extends('tenant.layouts.app')

@section('content')


    <tenant-machine-form
        @if(!empty($id))
    :id="{{$id}}"
        @endif
    ></tenant-machine-form>

@endsection
