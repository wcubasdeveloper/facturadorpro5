@extends('tenant.layouts.app')

@section('content')
    <?php
    use App\Models\Tenant\Configuration;
    $configuration = Configuration::first();
    ?>
<div class="page-header pr-0">
    <h2>
        <a href="/dashboard">
            <i class="fas fa-home"></i>
        </a>
    </h2>
    <ol class="breadcrumbs">
        <li class="active">
            <span>Dashboard</span>
        </li>
        <li>
            <span class="text-muted">Configuración</span>
        </li>
    </ol>
</div>

<div class="row">
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">General</h6>
                <ul class="card-report-links">
                        @if($user->type != 'integrator')
                    <li>
                        <a href="{{ url('list-banks') }}">Listado de bancos</a>
                    </li>
                    <li>
                        <a href="{{url('list-bank-accounts')}}">Listado de cuentas bancarias</a>
                    </li>
                    <li>
                        <a href="{{url('list-currencies')}}">Lista de monedas</a>
                    </li>
                    <li>
                        <a href="{{url('list-cards')}}">Listado de tarjetas</a>
                    </li>
                    <li>
                        <a href="{{url('list-platforms')}}">Plataformas</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
        @if(!empty($companyMenu))
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">Empresa</h6>
                <ul class="card-report-links">
                    <li>
                        <a href="{{route('tenant.companies.create')}}">Empresa</a>
                    </li>
                    <li>
                        <a href="{{route('tenant.bussiness_turns.index')}}">Giro de negocio</a>
                    </li>
                    <li>
                        <a href="{{route('tenant.advanced.index')}}">Avanzado</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        @endif
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">SUNAT</h6>
                <ul class="card-report-links">
                        @if($user->type != 'integrator')
                    <li>
                        <a href="{{url('list-attributes')}}">Listado de Atributos</a>
                    </li>
                    <li>
                        <a href="{{url('list-detractions')}}">Listado de tipos de detracciones</a>
                    </li>
                    <li>
                        <a href="{{url('list-units')}}">Listado de unidades</a>
                    </li>
                    <li>
                        <a href="{{url('list-transfer-reason-types')}}">Tipos de motivos de transferencias</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">Ingresos/Egresos</h6>
                <ul class="card-report-links">
                        @if($user->type != 'integrator')
                    <li>
                        <a href="{{url('list-payment-methods')}}">Métodos de pago - ingreso / gastos</a>
                    </li>
                    <li>
                        <a href="{{url('list-incomes')}}">Motivos de ingresos / Gastos</a>
                    </li>
                    <li>
                        <a href="{{url('list-payments')}}">Listado de métodos de pago</a>
                    </li>
                    @endif
                        @if($user->type != 'integrator')
                    <li>
                        <a href="{{url('list-vouchers-type')}}">Tipos de comprobantes INGRESOS Y GASTOS</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">Plantillas PDF</h6>
                <ul class="card-report-links">
                    <li>
                        <a href="{{route('tenant.advanced.pdf_templates')}}">PDF</a>
                    </li>
                    <li>
                        <a href="{{route('tenant.advanced.pdf_ticket_templates')}}">PDF - Ticket</a>
                    </li>
                    <li>
                        <a href="{{route('tenant.advanced.pdf_preprinted_templates')}}">Pre Impresos</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        @if(!empty($advanceMenu))
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">Avanzado</h6>
                <ul class="card-report-links">
                            @if($user->type != 'integrator' && $vc_company->soap_type_id != '03')
                    <li>
                        <a href="{{route('tenant.tasks.index')}}">Tareas programadas</a>
                    </li>
                    @endif
                    @if($vc_company->soap_type_id != '03')
                    <li>
                        <a href="{{route('tenant.offline_configurations.index')}}">Modo offline</a>
                    </li>
                    <li>
                        <a href="{{route('tenant.series_configurations.index')}}">Numeración de facturación</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{route('tenant.company_accounts.create')}}">Avanzado - Contable</a>
                    </li>
                            @if($user->type != 'integrator' && $vc_company->soap_type_id != '03')
                    <li>
                        <a href="{{route('tenant.inventories.configuration.index')}}">Inventarios</a>
                    </li>
                    @endif
                            @if($user->type === 'admin')
                            <li>
                                <a href="{{route('tenant.sale_notes.configuration')}}">Nota de ventas</a>
                            </li>
                        @endif
                                @if($configuration->isMiTiendaPe()== true)
                                    <li>
                                        <a href="{{route('tenant.mi_tienda_pe.configuration.index')}}">
                                            MiTienda.PE
                                        </a>
                                    </li>
                                @endif

                </ul>
            </div>
        </div>
    </div>
        @endif
    @if (! $useLoginGlobal)
            @if(!empty($visualMenu))
    <div class="col-6 col-md-4 mb-4">
        <div class="card card-dashboard card-reports">
            <div class="card-body">
                <h6 class="card-title">Visual</h6>
                <ul class="card-report-links">
                                {{-- @if($user->type != 'integrator')
                    <li class="{{($path[0] === 'catalogs') ? 'nav-active' : ''}}">
                        <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                            Catálogos
                        </a>
                    </li>
                    @endif --}}
                    <li>
                        <a href="{{route('tenant.login_page')}}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
            @endif
    @endif
</div>
@endsection
