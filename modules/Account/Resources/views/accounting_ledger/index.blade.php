@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <tenant-ledger-accounts
                :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
            ></tenant-ledger-accounts>
        </div>
    </div>

@endsection
