@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-note
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :document="{{ json_encode($document) }}"></tenant-documents-note>

@endsection
