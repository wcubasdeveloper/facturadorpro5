@extends('tenant.layouts.app')

@section('content')
   <!-- <tenant-report-kardex-index></tenant-report-kardex-index> -->
    <tenant-report-kardex-master
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
    ></tenant-report-kardex-master>


@endsection

@push('scripts')
    <script></script>
@endpush
