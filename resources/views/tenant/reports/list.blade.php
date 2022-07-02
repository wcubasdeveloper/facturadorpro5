@extends('tenant.layouts.app')

@section('content')
    @php
        $show_extra_info_to_item  = (bool) $configuration->show_extra_info_to_item
    @endphp
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
                <span class="text-muted">Reportes</span>
            </li>
        </ol>
    </div>

    <div class="row">
        <!-- General -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">General</h6>
                    <ul class="card-report-links">
                        @if($vc_company->soap_type_id != '03')
                            <li>
                                <a href="{{route('tenant.consistency-documents.index')}}">
                                    Consistencia documentos
                                </a>
                            </li>
                            <li>
                                <a href="{{route('tenant.validate_documents.index')}}">
                                    Validador de documentos
                                </a>
                            </li>
                        @endif
                        @if(in_array('hotel', $vc_business_turns))
                            <li>
                                <a href="{{route('tenant.reports.document_hotels.index')}}">
                                    Giro negocio hoteles
                                </a>
                            </li>
                            <li>
                                <a href="{{route('tenant.reports.report_hotel.index')}}">
                                    Reporte de Habitaciones
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{route('tenant.reports.commercial_analysis.index')}}">
                                Análisis comercial
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.massive-downloads.index')}}">
                                Descarga masiva - documentos
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.download-tray.index')}}">
                                Bandeja descarga de reportes
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Compras -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Compras</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{route('tenant.reports.purchases.index')}}">
                                Compras totales
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.fixed-asset-purchases.index')}}">
                                Activos fijos
                            </a>
                        </li>

                        <li>
                            <a href="{{route('tenant.reports.purchases.items.index')}}">
                                Producto - busqueda individual
                            </a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.purchases.general_items.index')}}">
                                Productos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Ventas -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Ventas</h6>
                    <ul class="card-report-links">
                        @if($vc_company->soap_type_id != '03')
                            <li>
                                <a href="{{route('tenant.reports.sales.index')}}">Documentos</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{route('tenant.reports.customers.index')}}">Clientes</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.items.index')}}">Producto - busqueda individual</a>
                        </li>
                            @if($show_extra_info_to_item == true)
                                <li>
                                    <a href="{{route('tenant.reports.extra.items.index')}}">
                                        Producto - busqueda individual - Por atributos
                                        <el-tooltip
                                            class="item"
                                            content="Reporte con los campos opcionales del item"
                                            effect="dark"
                                            placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </a>
                                </li>
                            @endif
                        <li>
                            <a href="{{route('tenant.reports.general_items.index')}}">Productos</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.quotations.index')}}">Cotizaciones</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.sale_notes.index')}}">Notas de Venta</a>
                        </li>
                        @if($vc_company->soap_type_id != '03')
                            <li>
                                <a href="{{route('tenant.reports.document_detractions.index')}}">Detracciones</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{route('tenant.reports.sales_consolidated.index')}}">Consolidado de items</a>
                        </li>
                        
                        <li>
                            <a href="{{route('tenant.reports.tips.index')}}">Propinas</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
        <!-- Ventas/Comisiones -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Ventas/Comisiones</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{route('tenant.reports.user_commissions.index')}}">Utilidad ventas</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.commissions.index')}}">Ventas</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.commissions_detail.index')}}">Utilidad detallado</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Pedidos -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Pedidos</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{route('tenant.reports.order_notes_general.index')}}">General</a>
                        </li>
                        <li>
                            <a href="{{route('tenant.reports.order_notes_consolidated.index')}}">
                                Consolidado de items
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Guias -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Guias</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{route('tenant.reports.guides.index')}}">
                                Consolidado de items
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
