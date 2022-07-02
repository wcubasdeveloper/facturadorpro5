@extends('tenant.layouts.app')

@section('content')
    <tenant-order-forms-form
        :id="{{ json_encode($id) }}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-order-forms-form>
@endsection
