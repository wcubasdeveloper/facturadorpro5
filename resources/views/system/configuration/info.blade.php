@extends('system.layouts.app')

@section('content')

    <div class="row">
        <!--<div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <system-companies-form></system-companies-form>
        </div> -->
        <div class="col-6">

            <system-php-configuration
                :memory_bytes="'{!! $memory_in_byte !!}'"
                :memory_write="'{!! $memory_limit !!}'"
                :backtrack_limit="'{!! $pcre_backtrack_limit !!}'"
                :all_config="{{json_encode($all_config)}}"
            ></system-php-configuration>
        </div>
        <div class="col-6">
            <system-server-status></system-server-status>
        </div>
    </div>

@endsection
