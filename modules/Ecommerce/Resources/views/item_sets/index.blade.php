@extends('tenant.layouts.app')

@section('content')
    <tenant-ecommerce-item-sets-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-ecommerce-item-sets-index>
@endsection
