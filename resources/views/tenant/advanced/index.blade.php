@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <tenant-configurations-form
                :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
                    :type-user="{{ json_encode(auth()->user()->type) }}"></tenant-configurations-form>
        </div>
    </div>
@endsection
