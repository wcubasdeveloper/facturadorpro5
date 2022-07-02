<header class="header">
    <div class="logo-container">
        <div class="sidebar-toggle" data-toggle-class="sidebar-left-collapsed" data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fas fa-angle-left" aria-label="Toggle sidebar"></i>
            <i class="fas fa-angle-right" aria-label="Toggle sidebar"></i>
        </div>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
        <tenant-dialog-header-menu></tenant-dialog-header-menu>

    </div>
    <div class="header-right">

        <ul class="notifications mx-2">
            @if($vc_company->soap_type_id == "01")
                <li>
                    <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif" class="notification-icon text-secondary" data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE DEMOSTRACIÓN, pulse para ir a configuración">
                        <i class="fas fa-2x fa-toggle-off mr-2" style="font-size: 20px"></i>
                        <span>DEMO</span>
                    </a>
                </li>
            @elseif($vc_company->soap_type_id == "02")
                <li>
                    <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif" class="notification-icon text-secondary" data-toggle="tooltip" data-placement="bottom" title="SUNAT: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
                        <i class="text-success fas fa-2x fa-toggle-on mr-2" style="font-size: 20px; color: #28a745 !important"></i>
                        <span>PROD</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="@if(in_array('configuration', $vc_modules)){{route('tenant.companies.create')}}@else # @endif" class="notification-icon text-secondary" data-toggle="tooltip" data-placement="bottom" title="INTERNO: ENTORNO DE PRODUCCIÓN, pulse para ir a configuración">
                        <i class="text-info fas fa-2x fa-toggle-on mr-2" style="font-size: 20px; color: #398bf7!important;"></i>
                        <span>INT</span>
                    </a>
                </li>
            @endif
        </ul>

        <span class="separator"></span>
        <ul class="notifications">
            <li>
                <a href="{{ route('tenant_orders_index') }}" class="notification-icon text-secondary" data-toggle="tooltip" data-placement="bottom" title="Pedidos pendientes">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span class="badge badge-pill badge-info badge-up cart-item-count">{{ $vc_orders }}</span>
                </a>
            </li>
        </ul>

        @if($vc_document > 0 || $vc_document_regularize_shipping > 0 || $vc_finished_downloads > 0)
        <span class="separator"></span>
        <ul class="notifications">
            <li class="showed" id="dropdown-notifications">
                <a href="#" id="dn-toggle" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-bell text-secondary"></i>
                    <span class="badge {{ $vc_document == 0 && $vc_document_regularize_shipping == 0 && $vc_finished_downloads > 0 ? 'badge-info' : '' }}">!</span>
                </a>

                <div id="dn-menu" class="dropdown-menu notification-menu" style="">
                    <div class="notification-title"></div>

                    <div class="content">
                        <ul>
                            @if($vc_document > 0)
                            <li>
                                <a href="{{route('tenant.documents.not_sent')}}" class="clearfix">
                                    <div class="image">
                                        <div class="badge badge-pill badge-danger text-light">{{ $vc_document }}</div>
                                    </div>
                                    <span class="title">Comprobantes enviados/por enviar</span>
                                </a>
                            </li>
                            @endif
                            @if($vc_document_regularize_shipping > 0)
                            <li>
                                <a href="{{route('tenant.documents.regularize_shipping')}}" class="clearfix">
                                    <div class="image">
                                        <div class="badge badge-pill badge-warning text-light">
                                            {{ $vc_document_regularize_shipping }}
                                        </div>
                                    </div>
                                    <span class="title">Comprobantes pendientes de rectificación</span>
                                </a>
                            </li>
                            @endif
                            @if($vc_finished_downloads > 0)
                            <li>
                                <a href="{{route('tenant.reports.download-tray.index')}}" class="clearfix">
                                    <div class="image">
                                        <div class="badge badge-pill badge-info text-light">
                                            {{ $vc_finished_downloads }}
                                        </div>
                                    </div>
                                    <span class="title">Bandeja de descargas</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
        @endif

        <span class="separator"></span>
        <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
                <div class="profile-info" data-lock-name="{{ $vc_user->email }}" data-lock-email="{{ $vc_user->email }}">
                    <span class="name">{{ $vc_user->name }}</span>
                    <span class="role">{{ $vc_user->email }}</span>
                </div>
                <figure class="profile-picture">
                    {{-- <img src="{{asset('img/%21logged-user.jpg')}}" alt="Profile" class="rounded-circle" data-lock-picture="img/%21logged-user.jpg" /> --}}
                    <div class="border rounded-circle text-center" style="width: 25px;"><i class="fas fa-user"></i></div>
                </figure>
                {{-- <i class="fa custom-caret"></i> --}}
            </a>
            <div class="dropdown-menu">
                <ul class="list-unstyled mb-0">
                    @if(in_array('cuenta', $vc_modules))
                        @if(in_array('account_users_list', $vc_module_levels))
                        <li>
                            <a role="menuitem" href="{{route('tenant.payment.index')}}">
                                <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                                <span>Mis Pagos</span>
                            </a>
                        </li>
                        @endif
                    @endif
                    <li class="divider"></li>
                    <li>
                        {{--<a role="menuitem" href="#"><i class="fas fa-user"></i> Perfil</a>--}}
                        <a role="menuitem" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
{{--
<div class="container d-none d-sm-block">
    <div id="switcher-top" class="d-flex justify-content-center switcher-hover">
        <span class="text-white py-0 px-5 text-center"><i class="fas fa-plus fa-fw"></i>Acceso Rápido</span>
    </div>
</div>
<div class="container d-none d-sm-block">
    <div id="switcher-list" class="d-flex justify-content-center switcher-hover">
        <div class="row">
            <div class="px-3"><a class="py-3" href="{{ route('tenant.documents.create') }}"><i class="fas fa-fw fa-file-invoice" aria-hidden="true"></i> Nuevo Comprobante</a></div>
            <div class="px-3"><a class="py-3" href="{{ in_array('pos', $vc_modules) ? route('tenant.pos.index') : '#' }}"><i class="fas fa-fw fa-cash-register" aria-hidden="true"></i> POS</a></div>
            <div style="min-width: 220px;"></div>
            <div class="px-3"><a class="py-3" href="{{ in_array('configuration', $vc_modules) ? route('tenant.companies.create') : '#' }}"><i class="fas fa-fw fa-industry" aria-hidden="true"></i> Empresa</a></div>
            <div class="px-3"><a class="py-3" href="{{ in_array('establishments', $vc_modules) ? route('tenant.establishments.index') : '#' }}"><i class="fas fa-fw fa-warehouse" aria-hidden="true"></i> Establecimientos</a></div>
        </div>
    </div>
</div> --}}
