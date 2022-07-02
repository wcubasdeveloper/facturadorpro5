<?php

    use App\Models\Tenant\Configuration;

    $configuration = Configuration::first();
    $firstLevel = $path[0] ?? null;
    $secondLevel = $path[1] ?? null;
    $thridLevel = $path[2] ?? null;
?>
<aside id="sidebar-left"
       class="sidebar-left">
    <div class="sidebar-header">
        <a href="{{route('tenant.dashboard.index')}}"
           class="logo pt-2 pt-md-0">
            @if($vc_company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}"
                     alt="Logo"/>
            @else
                <img src="{{asset('logo/tulogo.png')}}"
                     alt="Logo"/>
            @endif
        </a>
        <div class="d-md-none toggle-sidebar-left"
             data-toggle-class="sidebar-left-opened"
             data-target="html"
             data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars"
               aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu"
                 class="nav-main"
                 role="navigation">
                <ul class="nav nav-main">
                    @if(in_array('dashboard', $vc_modules))
                        <li class="{{ ($firstLevel === 'dashboard')?'nav-active':'' }}">
                            <a class="nav-link"
                               href="{{ route('tenant.dashboard.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-airplay">
                                    <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                    <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                </svg>
                                <span>DASHBOARD</span>
                            </a>
                        </li>
                    @endif

                    {{-- Ventas --}}
                    @if(in_array('documents', $vc_modules))
                        <li class="
                        nav-parent
                        {{ ($firstLevel === 'documents')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'summaries')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'voided')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'quotations')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'sale-notes')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'contingencies')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'incentives')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'order-notes')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'sale-opportunities')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'contracts')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'production-orders')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'technical-services')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'user-commissions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'regularize-shipping')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16"
                                          y1="13"
                                          x2="8"
                                          y2="13"></line>
                                    <line x1="16"
                                          y1="17"
                                          x2="8"
                                          y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span>VENTAS</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                                    @if(in_array('documents', $vc_modules))
                                        @if(in_array('new_document', $vc_module_levels))
                                            <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'create')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.documents.create')}}">Comprobante
                                                                                               electrónico</a>
                                            </li>
                                        @endif
                                    @endif
                                @endif

                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')
                                    @if(in_array('list_document', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel != 'create' && $secondLevel != 'not-sent'&& $secondLevel != 'regularize-shipping')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.index')}}">Listado de comprobantes</a>
                                        </li>
                                    @endif
                                @endif

                                @if(in_array('sale_notes', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'sale-notes')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.sale_notes.index')}}">Notas de Venta</a>
                                    </li>
                                @endif

                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')

                                    @if(in_array('document_not_sent', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'not-sent')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.not_sent')}}">
                                                Comprobantes no enviados
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('regularize_shipping', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'regularize-shipping')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.regularize_shipping')}}">
                                                CPE pendientes de rectificación
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if(auth()->user()->type != 'integrator' && in_array('documents', $vc_modules) )

                                    @if(auth()->user()->type != 'integrator' && in_array('document_contingengy', $vc_module_levels) && $vc_company->soap_type_id != '03')
                                        <li class="{{ ($firstLevel === 'contingencies' )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.contingencies.index')}}">
                                                Documentos de contingencia
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('summary_voided', $vc_module_levels) && $vc_company->soap_type_id != '03')

                                        <li class="nav-parent
                                        {{ ($firstLevel === 'summaries')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'voided')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Resúmenes y Anulaciones
                                            </a>
                                            <ul class="nav nav-children">
                                                <li class="{{ ($firstLevel === 'summaries')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.summaries.index')}}">
                                                        Resúmenes
                                                    </a>
                                                </li>
                                                <li class="{{ ($firstLevel === 'voided')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.voided.index')}}">
                                                        Anulaciones
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif

                                    @if(in_array('sale-opportunity', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'sale-opportunities')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.sale_opportunities.index')}}">
                                                Oportunidad de venta
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('quotations', $vc_module_levels))

                                        <li class="{{ ($firstLevel === 'quotations')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.quotations.index')}}">
                                                Cotizaciones
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('contracts', $vc_module_levels))
                                        <li class="nav-parent
                                        {{ ($firstLevel === 'contracts')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'production-orders')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Contratos
                                            </a>
                                            <ul class="nav nav-children">
                                                <li class="{{ ($firstLevel === 'contracts')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.contracts.index')}}">
                                                        Listado
                                                    </a>
                                                </li>
                                                <li class="{{ ($firstLevel === 'production-orders')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.production_orders.index')}}">
                                                        Ordenes de Producción
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif

                                    @if(in_array('order-note', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'order-notes')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.order_notes.index')}}">
                                                Pedidos
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('technical-service', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'technical-services')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.technical_services.index')}}">
                                                Servicio de soporte técnico
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('incentives', $vc_module_levels))

                                        <li class="nav-parent
                                        {{ ($firstLevel === 'incentives')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'user-commissions')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Comisiones
                                            </a>
                                            <ul class="nav nav-children">
                                                <li class="{{ ($firstLevel === 'user-commissions')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.user_commissions.index')}}">
                                                        Vendedores
                                                    </a>
                                                </li>
                                                <li class="{{ ($firstLevel === 'incentives')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.incentives.index')}}">Productos</a>
                                                </li>
                                            </ul>
                                        </li>

                                    @endif



                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- POS --}}
                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('pos', $vc_modules))
                            <li class="nav-parent
                                {{ ($firstLevel === 'pos')?'nav-active nav-expanded':'' }}
                                {{ ($firstLevel === 'cash')?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-shopping-cart">
                                        <circle cx="9"
                                                cy="21"
                                                r="1"></circle>
                                        <circle cx="20"
                                                cy="21"
                                                r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>POS</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('pos', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'pos' && !$secondLevel )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.pos.index')}}">Punto de venta</a>
                                        </li>
                                    @endif
                                    @if(in_array('pos_garage', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'pos' && $secondLevel === 'garage')?'nav-active':'' }}">
                                            <a class="nav-link"
                                                href="{{route('tenant.pos.garage')}}">Venta rápida <span style="font-size:.65rem;">(Grifos y Markets)</span></a>
                                        </li>
                                    @endif
                                    @if(in_array('cash', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'cash')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.cash.index')}}">Caja chica POS</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- Tienda virtual --}}
                    @if(in_array('ecommerce', $vc_modules))
                        <li class="nav-parent
                        {{ in_array($firstLevel, ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'nav-active nav-expanded':'' }}"
                        >
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Tienda Virtual</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('ecommerce', $vc_module_levels))
                                    <li class="">
                                        <a class="nav-link"
                                           onclick="window.open( '{{ route("tenant.ecommerce.index") }} ')">Ir a Tienda</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_orders', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'orders')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant_orders_index')}}">Pedidos</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_items', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'items_ecommerce')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.items_ecommerce.index')}}">Productos Tienda Virtual</a>
                                    </li>
                                @endif

                                <li class="{{ ( $secondLevel === 'item-sets')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.ecommerce.item_sets.index')}}">Conjuntos/Packs/Promociones</a>
                                </li>

                                @if(in_array('ecommerce_tags', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'tags')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.tags.index')}}">Tags - Categorias(Etiquetas)</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_promotions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'promotions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.promotion.index')}}">Promociones(Banners)</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_settings', $vc_module_levels))
                                    <li class="{{ ($secondLevel === 'configuration')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant_ecommerce_configuration')}}">Configuración</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Productos --}}
                    @if(in_array('items', $vc_modules))
                        <li class="nav-parent
                        {{ ($firstLevel === 'items')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'services')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'categories')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'brands')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'item-lots')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'item-sets')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-grid">
                                    <rect x="3"
                                          y="3"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="14"
                                          y="3"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="14"
                                          y="14"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="3"
                                          y="14"
                                          width="7"
                                          height="7"></rect>
                                </svg>
                                <span>Productos/Servicios</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('items', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'items')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.items.index')}}">Productos</a>
                                    </li>
                                @endif
                                @if(in_array('items_packs', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'item-sets'  )?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.item_sets.index')}}">Conjuntos/Packs/Promociones</a>
                                    </li>
                                @endif
                                @if(in_array('items_services', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'services')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.services')}}">Servicios</a>
                                    </li>
                                @endif
                                @if(in_array('items_categories', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'categories')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.categories.index')}}">Categorías</a>
                                    </li>
                                @endif
                                @if(in_array('items_brands', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'brands')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.brands.index')}}">Marcas</a>
                                    </li>
                                @endif
                                @if(in_array('items_lots', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'item-lots')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.item-lots.index')}}">Series</a>
                                    </li>
                                @endif

                                    <li class="{{ ($firstLevel === 'zones')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.zone.index')}}">Zonas</a>
                                    </li>

                            </ul>
                        </li>
                    @endif
                    {{-- Clientes --}}
                    @if(in_array('persons', $vc_modules))
                        <li class="nav-parent
                        {{ ($firstLevel === 'persons' && $secondLevel === 'customers')?'nav-active nav-expanded':'' }}
                        {{ $firstLevel === 'person-types' ? 'nav-active nav-expanded' : '' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-user-check">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5"
                                            cy="7"
                                            r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                                <span>Clientes</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('clients', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'persons' && $secondLevel === 'customers')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.persons.index', ['type' => 'customers'])}}">Clientes</a>
                                    </li>
                                @endif
                                @if(in_array('clients_types', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'person-types')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.person_types.index')}}">Tipos de clientes</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('purchases', $vc_modules))
                            <li class="
                            nav-parent
                            {{ (
	                            $firstLevel === 'purchases' ||
                                ($firstLevel === 'persons' && $secondLevel === 'suppliers') ||
                                $firstLevel === 'expenses' ||
                                $firstLevel === 'bank_loan' ||
                                $firstLevel === 'purchase-quotations' ||
                                $firstLevel === 'fixed-asset'
                                ) ?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-shopping-bag">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3"
                                              y1="6"
                                              x2="21"
                                              y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span>Compras</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('purchases_create', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchases' && $secondLevel === 'create')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.create')}}">Nuevo</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_list', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchases' && $secondLevel != 'create')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.index')}}">Listado</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_orders', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchase-orders')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchase-orders.index')}}">Ordenes de compra</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_expenses', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'bank_loan' )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.bank_loan.index')}}">Credito Bancario</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_expenses', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'expenses' )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.expenses.index')}}">Gastos diversos</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_quotations', $vc_module_levels) || in_array('purchases_suppliers', $vc_module_levels))
                                        <li class="nav-parent
                                    {{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Proveedores
                                            </a>
                                            <ul class="nav nav-children">
                                                @if(in_array('purchases_suppliers', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}">Listado</a>
                                                    </li>
                                                @endif
                                                @if(in_array('purchases_quotations', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'purchase-quotations')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.purchase-quotations.index')}}">Solicitar cotización</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels) || in_array('purchases_fixed_assets_items', $vc_module_levels))
                                        <li class="nav-parent {{ ($firstLevel === 'fixed-asset' )?'nav-active nav-expanded' : '' }}">
                                            <a class="nav-link"
                                               href="#">Activos fijos</a>
                                            <ul class="nav nav-children">
                                                @if(in_array('purchases_fixed_assets_items', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'fixed-asset' && $secondLevel === 'items')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.fixed_asset_items.index')}}">Ítems</a>
                                                    </li>
                                                @endif
                                                @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'fixed-asset' && $secondLevel === 'purchases')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.fixed_asset_purchases.index')}}">Compras</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- Inventario --}}
                        @if(in_array('inventory', $vc_modules))
                            <li class="nav-parent
                            {{ (in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions', 'extra_info_items']) |($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])))?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-archive">
                                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                        <rect x="1"
                                              y="3"
                                              width="22"
                                              height="5"></rect>
                                        <line x1="10"
                                              y1="12"
                                              x2="14"
                                              y2="12"></line>
                                    </svg>
                                    <span>Inventario</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('inventory', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'inventory')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('inventory.index')}}">Movimientos</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_transfers', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'transfers')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('transfers.index')}}">Traslados</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_devolutions', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'devolutions')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('devolutions.index')}}">Devolucion a proveedor</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_kardex', $vc_module_levels))
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel === 'kardex')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.kardex.index')}}">Reporte Kardex</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report', $vc_module_levels))
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel == 'inventory')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.inventory.index')}}">Reporte Inventario</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_kardex', $vc_module_levels))
                                        {{-- <li class="{{ ($firstLevel === 'warehouses')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('warehouses.index')}}">Almacenes</a>
                                        </li> --}}
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel === 'valued-kardex')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.valued_kardex.index')}}">Kardex valorizado</a>
                                        </li>
                                    @endif
                                        @if(in_array('production_app', $vc_modules) && $configuration->isShowExtraInfoToItem())
                                        <li class="{{($firstLevel === 'extra_info_items') ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('extra_info_items.index')}}">Datos extra de items</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                    @endif

                    @if(in_array('establishments', $vc_modules))
                        <li class="nav-parent {{ in_array($firstLevel, ['users', 'establishments'])?'nav-active nav-expanded':'' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9"
                                            cy="7"
                                            r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Usuarios/Locales & Series</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('users', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'users')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.users.index')}}">Usuarios</a>
                                    </li>
                                @endif
                                @if(in_array('users_establishments', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'establishments')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.establishments.index')}}">Establecimientos</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(in_array('advanced', $vc_modules) && $vc_company->soap_type_id != '03')
                        <li class="
                        nav-parent
                        {{ ($firstLevel === 'retentions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'dispatches')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'perceptions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'drivers')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'dispatchers')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'order-forms')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'purchase-settlements')?'nav-active nav-expanded':'' }}

                            ">
                            <a class="nav-link"
                               href="#">
                                <i class="fas fa-receipt"
                                   aria-hidden="true"></i>
                                <span>Comprobantes avanzados</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('advanced_retentions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'retentions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.retentions.index')}}">Retenciones</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_dispatches', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'dispatches')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.dispatches.index')}}">Guías de remisión</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_perceptions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'perceptions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.perceptions.index')}}">Percepciones</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_purchase_settlements', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'purchase-settlements')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.purchase-settlements.index')}}">Liquidaciones de
                                                                                                 compra</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_order_forms', $vc_module_levels))
                                    <li class="nav-parent
                                {{ ($firstLevel === 'order-forms')?'nav-active nav-expanded':'' }}
                                    {{ ($firstLevel === 'drivers')?'nav-active nav-expanded':'' }}
                                    {{ ($firstLevel === 'dispatchers')?'nav-active nav-expanded':'' }}
                                        ">
                                        <a class="nav-link"
                                           href="#">Ordenes de pedido</a>
                                        <ul class="nav nav-children">
                                            <li class="{{ ($firstLevel === 'order-forms')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.index')}}">Listado</a>
                                            </li>
                                            <li class="{{ ($firstLevel === 'drivers')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.drivers.index')}}">Conductores</a>
                                            </li>
                                            <li class="{{ ($firstLevel === 'dispatchers')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.dispatchers.index')}}">Transportistas</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(in_array('reports', $vc_modules))
                        <li class="{{  ($firstLevel === 'reports' && in_array($secondLevel, ['purchases', 'search','sales','customers','items', 'general-items','consistency-documents', 'quotations', 'sale-notes','cash','commissions','document-hotels', 'validate-documents', 'document-detractions','commercial-analysis', 'order-notes-consolidated', 'order-notes-general', 'sales-consolidated', 'user-commissions', 'fixed-asset-purchases', 'massive-downloads', 'tips'])) ? 'nav-active' : ''}} {{ $firstLevel === 'list-reports' ? 'nav-active' : '' }}">
                            <a class="nav-link"
                               href="{{ url('list-reports') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-pie-chart">
                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                </svg>
                                <span>Reportes</span>
                            </a>
                        </li>
                    @endif

                    @if(in_array('accounting', $vc_modules))
                        <li class="nav-parent {{ ($firstLevel === 'account' || $firstLevel === 'accounting_ledger'  )?'nav-active nav-expanded':'' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-bar-chart-2">
                                    <line x1="18"
                                          y1="20"
                                          x2="18"
                                          y2="10"></line>
                                    <line x1="12"
                                          y1="20"
                                          x2="12"
                                          y2="4"></line>
                                    <line x1="6"
                                          y1="20"
                                          x2="6"
                                          y2="14"></line>
                                </svg>
                                <span>Contabilidad</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('account_report', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel === 'format')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account_format.index') }}">Exportar reporte</a>
                                    </li>
                                @endif
                                @if(in_array('account_formats', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == ''))   ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account.index') }}">Exportar formatos - Sis.
                                                                                      Contable</a>
                                    </li>
                                @endif
                                @if(in_array('account_summary', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == 'summary-report'))   ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account_summary_report.index') }}">Reporte resumido -
                                                                                                     Ventas</a>
                                    </li>
                                @endif
                                <li class="{{(($firstLevel === 'accounting_ledger') )   ? 'nav-active' : ''}}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.accounting_ledger.create') }}">
                                        Libro Mayor
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(in_array('finance', $vc_modules))

                        <li class="nav-parent {{ $firstLevel === 'finances' && in_array($secondLevel, [
                                                	'global-payments',
                                                	 'balance',
                                                	 'payment-method-types',
                                                	 'unpaid',
                                                	 'to-pay',
                                                	 'income',
                                                	 'transactions',
                                                	 'movements'
                                                	 ]) ? 'nav-active nav-expanded' : ''}}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-dollar-sign">
                                    <line x1="12"
                                          y1="1"
                                          x2="12"
                                          y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>Finanzas</span>
                            </a>
                            <ul class="nav nav-children">

                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'movements')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.movements.index')}}">Movimientos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'transactions')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.transactions.index')}}">Transacciones</a>
                                    </li>
                                @endif
                                @if(in_array('finances_incomes', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'income')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.income.index')}}">Ingresos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_unpaid', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'unpaid')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.unpaid.index')}}">Cuentas por cobrar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_to_pay', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'to-pay')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.to_pay.index')}}">Cuentas por pagar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payments', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'global-payments')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.global_payments.index')}}">Pagos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_balance', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'balance')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.balance.index')}}">Balance</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payment_method_types', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'payment-method-types')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.payment_method_types.index')}}">Ingresos y Egresos - M. Pago</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(in_array('configuration', $vc_modules))
                        <li class="{{in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type',     'companies', 'advanced', 'tasks', 'inventories','bussiness_turns','offline-configurations','series-configurations','configurations', 'login-page', 'list-settings']) ? 'nav-active' : ''}}">
                            <a class="nav-link"
                               href="{{ url('list-settings') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-settings">
                                    <circle cx="12"
                                            cy="12"
                                            r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                <span>Configuración</span>
                            </a>
                        </li>
                    @endif

                    {{-- @if(in_array('cuenta', $vc_modules))
                    <li class=" nav-parent
                        {{ ($firstLevel === 'cuenta')?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            <span>Mis Pagos</span>
                        </a>
                        <ul class="nav nav-children">
                            @if(in_array('account_users_settings', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'cuenta') && ($secondLevel === 'configuration')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.configuration.index')}}">Configuracion</a>
                            </li>
                            @endif
                            @if(in_array('account_users_list', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'cuenta') && ($secondLevel === 'payment_index')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.payment.index')}}">Lista de Pagos</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif --}}
                    {{-- @if(in_array('hotels', $vc_modules) || in_array('documentary-procedure', $vc_modules))
                    <li class="nav-description">Módulos extras</li>
                    @endif --}}
                    @if(in_array('hotels', $vc_modules))
                        <li class=" nav-parent {{ ($firstLevel === 'hotels') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fas fa-building"
                                   aria-hidden="true"></i>
                                <span>Hoteles</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('hotels_reception', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'reception')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/reception') }}">Recepción</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rates', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'rates')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/rates') }}">Tarifas</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_floors', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'floors')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/floors') }}">Pisos</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_cats', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'categories')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/categories') }}">Categorías</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rooms', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'rooms')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/rooms') }}">Habitaciones</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(in_array('documentary-procedure', $vc_modules))
                        <li class=" nav-parent {{ ($firstLevel === 'documentary-procedure') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-folder">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Trámite documentario</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('documentary_offices', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'offices')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.offices') }}">Listado de Etapas</a>
                                    </li>
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'status')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.status') }}">Listado de Estados</a>
                                    </li>
                                @endif
                                    @if(in_array('documentary_process', $vc_module_levels))


                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'requirements')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.requirements') }}">Listado de requisitos</a>
                                    </li>

                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'processes')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.processes') }}">Tipos de Trámites</a>
                                    </li>
                                @endif
                                {{--
                            @if(in_array('documentary_documents', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'documents')) ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{ route('documentary.documents') }}">Tipos de Documento</a>
                            </li>
                            @endif
                            @if(in_array('documentary_actions', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'actions')) ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{ route('documentary.actions') }}">Acciones</a>
                            </li>
                            @endif
                                --}}
                                @if(in_array('documentary_files', $vc_module_levels))
                                    {{--
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'files')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.files') }}">Listado de Trámites</a>
                                    </li>
                                    --}}
                                    <li class="{{ (($firstLevel === 'documentary-procedure') &&( ($secondLevel === 'files_simplify')||($secondLevel === 'files'))) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.files_simplify') }}">Listado de Trámites</a>
                                    </li>
                                    <li class="{{ (($firstLevel === 'documentary-procedure') &&( ($secondLevel === 'stadistic'))) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.stadistic') }}">Estadisticas de Trámites</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- DIGEMID --}}
                    @if(in_array('digemid', $vc_modules) && $configuration->isPharmacy())
                        <li class=" nav-parent {{ ($firstLevel === 'digemid') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fa fas fa-ambulance"
                                   aria-hidden="true"></i>
                                <span>Farmacia</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('digemid', $vc_module_levels))
                                    {{-- <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'offices')) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ route('documentary.offices') }}">Oficinas</a>
                                    </li> --}}
                                    <li class="{{ (($firstLevel === 'digemid') && ($secondLevel === 'digemid')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.digemid.index') }}">Productos</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Suscription --}}
                    @if(in_array('full_suscription_app', $vc_modules) )
                        <li class=" nav-parent {{ ($firstLevel === 'full_suscription') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fa fas fa-calendar-check"
                                   aria-hidden="true"></i>
                                <span>
                                    Suscripción Servicios SAAS
                                </span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($firstLevel === 'full_suscription' && $secondLevel === 'client')?'nav-active':'' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.fullsuscription.client.index') }}">
                                    Clientes
                                    </a>
                                </li>
                                <li class="{{ (($firstLevel === 'full_suscription') && ($secondLevel === 'plans')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.fullsuscription.plans.index') }}">
                                        Planes
                                    </a>
                                </li>
                                <li class="{{ (($firstLevel === 'full_suscription') && ($secondLevel === 'payments')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.fullsuscription.payments.index') }}">
                                        Suscripciones
                                    </a>
                                </li>
                                <li class="{{ (($firstLevel === 'full_suscription') && ($secondLevel === 'payment_receipt')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.fullsuscription.payment_receipt.index') }}">
                                        Recibos de pago
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    {{-- Suscription Escolar--}}
                    @if(in_array('suscription_app', $vc_modules) )
                        <li class=" nav-parent {{ ($firstLevel === 'suscription') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fa fas fa-calendar-check"
                                   aria-hidden="true"></i>
                                <span>Suscripción Escolar</span>
                            </a>
                            <ul class="nav nav-children">
                                {{--                                @if(in_array('suscription_app_client', $vc_module_levels))--}}
                                <li class="nav-parent {{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client') ) ? ' nav-active nav-expanded ' : '' }}
                                    ">

                                    <a class="nav-link"
                                       href="#">
                                        Clientes
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="{{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client')  && ($thridLevel !== 'childrens') )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{ route('tenant.suscription.client.index') }}">
                                                Padres
                                            </a>
                                        </li>
                                        <li class="{{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client') && ($thridLevel === 'childrens') )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{ route('tenant.suscription.client_children.index') }}">
                                                Hijos
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                {{--                                @endif--}}
                                {{--
                                @todo suscription_app_service borrar de modulo de permisos admin y cliente

                                @if(in_array('suscription_app_service', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'service')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.suscription.service.index') }}">
                                            Servicio
                                        </a>
                                    </li>
                                @endif
                                    --}}
                                {{--                                    @if(in_array('suscription_app_plans', $vc_module_levels))--}}
                                <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'plans')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.suscription.plans.index') }}">
                                        Planes
                                    </a>
                                </li>
                                {{--                                    @endif--}}

                                {{--                                   @if(in_array('suscription_app_payments', $vc_module_levels))--}}
                                <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'payments')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.suscription.payments.index') }}">
                                        Matrículas
                                    </a>
                                </li>
                                {{--                                @endif--}}
                                {{--                                   @if(in_array('suscription_app_payments', $vc_module_levels))--}}
                                <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'payment_receipt')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.suscription.payment_receipt.index') }}">
                                        Recibos de pago
                                    </a>
                                </li>
                                {{--                                @endif--}}
                            </ul>
                        </li>
                    @endif



                    {{-- Produccion --}}
                    @if(in_array('production_app', $vc_modules) )

                        <li class=" nav-parent {{ (
                                                    ($firstLevel === 'production') ||
                                                    ($firstLevel === 'machine-production') ||
                                                    ($firstLevel === 'packaging') ||
                                                    ($firstLevel === 'machine-type-production') ||
                                                    ($firstLevel === 'workers') ||
                                                    ($firstLevel === 'mill-production')
                                                ) ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                                href="#">
                                <i class="fa fas fa-calendar-check"
                                    aria-hidden="true"></i>
                                <span>Producción</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ (($firstLevel === 'production') ) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.production.index') }}">
                                        Productos Fabricados
                                    </a>
                                </li>
                                <li class="{{ (($firstLevel === 'mill-production')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.mill_production.index') }}">
                                        Ingreso de Insumos
                                    </a>
                                </li>

                                <li class="{{ (($firstLevel === 'machine-type-production')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.machine_type_production.index') }}">
                                        Tipos de maquinaria
                                    </a>
                                </li>


                                <li class="{{ (($firstLevel === 'machine-production')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.machine_production.index') }}">
                                        Maquinaria
                                    </a>
                                </li>
                                <li class="{{ (($firstLevel === 'packaging')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.packaging.index') }}">
                                        Zona de embalaje
                                    </a>
                                </li>

                                <li class="{{ (($firstLevel === 'workers')) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.workers.index') }}">
                                        Empleados
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{-- Restaurante --}}
                    @if(in_array('restaurant_app', $vc_modules))
                        <li class=" nav-parent {{ ($firstLevel === 'restaurant') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fas fa-utensils"
                                   aria-hidden="true"></i>
                                <span>Restaurante</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="nav-parent
                                {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos')?'nav-active nav-expanded':'' }}">
                                    <a class="nav-link"
                                        href="#">
                                        POS
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="{{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == 'pos')?'nav-active':'' }}">
                                            <a class="nav-link"
                                                href="{{route('tenant.restaurant.cash.filter-pos')}}">
                                                Caja Chica
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-parent {{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '')?'nav-active nav-expanded':'' }}">
                                    <a class="nav-link"
                                        href="#">
                                        Mesas
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="{{ ($secondLevel != null && $secondLevel == 'cash' && $thridLevel == '')?'nav-active':'' }}">
                                            <a class="nav-link"
                                                href="{{route('tenant.restaurant.cash.index')}}">
                                                Caja Chica
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-parent
                                {{ ( $secondLevel != null && $secondLevel == 'promotions') || ( $secondLevel != null && $secondLevel == 'orders') ?'nav-active nav-expanded':'' }}">
                                    <a class="nav-link"
                                        href="#">
                                        Pedidos
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="">
                                            <a class="nav-link"
                                                href="{{ route('tenant.restaurant.menu') }}"
                                                target="blank">
                                                Ver Menu
                                            </a>
                                        </li>
                                        <li class="{{ ( $secondLevel != null && $secondLevel == 'promotions')?'nav-active':'' }}">
                                            <a class="nav-link"
                                                href="{{route('tenant.restaurant.promotion.index')}}">
                                                Promociones(Banners)
                                            </a>
                                        </li>
                                        <li class="{{ ( $secondLevel != null && $secondLevel == 'orders')?'nav-active':'' }}">
                                            <a class="nav-link"
                                                href="{{route('tenant.restaurant.order.index')}}">
                                                Pedidos
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="{{ ( $secondLevel != null && $secondLevel == 'list' && $firstLevel === 'restaurant' ) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.restaurant.list_items') }}">
                                        Productos
                                    </a>
                                </li>
                                <li class="{{ ( $secondLevel != null && $secondLevel == 'configuration' && $firstLevel === 'restaurant' ) ? 'nav-active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('tenant.restaurant.configuration') }}">
                                        Configuración
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(in_array('generate_link_app', $vc_modules))
                        <li class="{{ ($firstLevel === 'payment-links')?'nav-active':'' }}">
                            <a class="nav-link"
                               href="{{ route('tenant.payment.generate.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-share-2">
                                    <circle cx="18"
                                        cy="5"
                                        r="3"></circle>
                                    <circle cx="6"
                                        cy="12"
                                        r="3"></circle>
                                    <circle cx="18"
                                        cy="19"
                                        r="3"></circle>
                                    <line x1="8.59"
                                        y1="13.51"
                                        x2="15.42"
                                        y2="17.49"></line>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                </svg>
                                <span>Generador de link de pago</span>
                            </a>
                        </li>
                    @endif

                    {{-- APP --}}
                    @if(in_array('apps', $vc_modules))
                        <li class="">
                            <a class="nav-link"
                               href="{{url('list-extras')}}">
                                <i class="fas fa-cube"></i>
                                <span>Apps</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>
