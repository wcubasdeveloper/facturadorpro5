@extends('tenant.layouts.app')

@section('content')

    <tenant-full-suscription-plans-index
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :date="'{{Carbon\Carbon::now()->format('Y-m-d')}}'"
    ></tenant-full-suscription-plans-index>




@endsection
