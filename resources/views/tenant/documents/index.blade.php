@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-index :is-client="{{ json_encode($is_client) }}"
                            :type-user="{{ json_encode(auth()->user()->type) }}"
                            :import_documents="{{ json_encode($import_documents) }}"
                            user-id="{{ auth()->user()->id }}"
                            :user-permission-edit-cpe="{{ json_encode(auth()->user()->permission_edit_cpe) }}"
                            :import_documents_second="{{ json_encode($import_documents_second) }}"
                            :configuration="{{ $configuration }}"
                            :view_apiperudev_validator_cpe="{{ json_encode($view_apiperudev_validator_cpe) }}"
                            :view_validator_cpe="{{ json_encode($view_validator_cpe) }}"></tenant-documents-index>

@endsection

@push('scripts')
<script type="text/javascript">
	$(function(){
    'use strict';
        $(".tableScrollTop,.tableWide-wrapper").scroll(function(){
            $(".tableWide-wrapper,.tableScrollTop")
                .scrollLeft($(this).scrollLeft());
        });
    });
</script>
@endpush
