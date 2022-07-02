@extends('tenant.layouts.app')

@section('content')

    <tenant-finance-movements-index
        :ismovements="{{$isMovements}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-finance-movements-index>

@endsection
