@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-files
        :local_files='@json($files)'
        :local_offices='@json($offices)'
        :local_processes='@json($processes)'
        :local_actions='@json($actions)'
        :local_customers='@json($customers)'
        :local_document-types='@json($documentTypes)'

    ></tenant-documentary-files>
@endsection
