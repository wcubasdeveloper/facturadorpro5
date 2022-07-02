@extends('tenant.layouts.app')

@section('content')

    <tenant-configurations-sale-notes
        :url="'{{$migrationConfiguration['url']}}'"
        :api-key="'{{$migrationConfiguration['api_key']}}'"
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-configurations-sale-notes>

@endsection
