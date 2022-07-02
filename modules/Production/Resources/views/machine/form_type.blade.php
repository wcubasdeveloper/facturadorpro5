@extends('tenant.layouts.app')

@section('content')

    <tenant-machine-type-form
        @if(!empty($id))
        :id="{{$id}}"
        @endif></tenant-machine-type-form>

@endsection
