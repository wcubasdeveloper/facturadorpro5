@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-create
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :order_form_id="{{ json_encode($order_form_id) }}"
    ></tenant-dispatches-create>
@endsection
