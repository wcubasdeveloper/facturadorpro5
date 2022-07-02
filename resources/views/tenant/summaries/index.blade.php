@extends('tenant.layouts.app')

@section('content')

    <tenant-summaries-index :show_summary_status_type="{{ json_encode(config('tenant.show_summary_status_type')) }}"></tenant-summaries-index>

@endsection