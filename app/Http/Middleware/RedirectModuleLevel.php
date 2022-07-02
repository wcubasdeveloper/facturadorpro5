<?php

    namespace App\Http\Middleware;

    use App\Models\Tenant\User;
    use Closure;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Modules\LevelAccess\Models\ModuleLevel;

    /**
     * Class RedirectModuleLevel
     * Debe aplicarse el middleware ->middleware('redirect.level'); a la ruta
     * Controla los niveles de acceso desde el modulo de administracion.
     *
     * @package App\Http\Middleware
     */
    class RedirectModuleLevel
    {
        /**
         * Handle an incoming request.
         *
         * @param Request $request
         * @param Closure $next
         *
         * @return mixed
         */
        public function handle($request, Closure $next)
        {

            /** @var User $user */
            $user = $request->user();
            $level = $user->getLevel();
            $path = explode('/', $request->path());
            $levels = $user->getLevels();

            if (!$request->ajax()) {

                if (count($levels) != 0) {
                    // dd("w");

                    /** Se comenta el limite para poder aceptar todos los filtros cuando se añadan,
                     * tambien el superior es diferente a 0 para que evalue cuando existan niveles de module_levels
                     */
                    //if (count($levels) < 72) {
                    // dd($levels);

                    $group = $this->getGroup($path, $level);
                    // dd($group);

                    if ($group) {
                        if ($this->getLevelByGroup($levels, $group) === 0) {
                            $this->fixPermissions($level, $path);
                            return $this->redirectRoute($level);
                        }

                    }
                    // }

                }
            }

            return $next($request);

        }

        /**
         * @param $path
         * @param $module
         *
         * @return string|null
         */
        private function getGroup($path, $module)
        {

            ///* Module Documents */
            // dd($path[1]);
            $group = null;
            $firstLevel = $path[0] ?? null;
            $secondLevel = $path[1] ?? null;

            if (isset($path[1])) {

                if ($path[0] == "documents" && $path[1] == "create") {
                    $group = "new_document";
                } else {
                    if ($path[0] == "documents" && $path[1] == "not-sent") {
                        $group = "document_not_sent";
                    } //customers
                    else {
                        if ($path[0] == "persons" && $path[1] == "customers") {
                            $group = "catalogs";
                        } else {
                            if ($path[0] == "quotations" && $path[1] == "create") {
                                $group = "quotations";
                            } else {
                                if ($path[0] == "quotations" && $path[1] == "edit") {
                                    $group = "quotations";
                                } else {
                                    if ($path[0] == "sale-notes" && $path[1] == "create") {
                                        $group = "sale_notes";
                                    } else {
                                        if ($path[0] == "contracts" && $path[1] == "create") {
                                            $group = "contracts";
                                        } else {
                                            if ($path[0] == "sale-opportunities" && $path[1] == "create") {
                                                $group = "sale-opportunity";
                                            } else {
                                                if ($path[0] == "order-notes" && $path[1] == "create") {
                                                    $group = "order-note";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /** Configuracion avanzada */
                if (
                    ($firstLevel == "company_accounts" && $secondLevel == 'create') ||
                    ($firstLevel == "inventories" && $secondLevel == 'configuration') ||
                    ($firstLevel == "configurations" && $secondLevel == 'sale-notes')
                ) {
                    $group = "configuration_advance";
                }
                /** Giro de negocio */
                if (
                    ($firstLevel == "companies" && $secondLevel == 'create')

                ) {
                    $group = "configuration_company";
                }

            } else {
                /** Documentos */
                if ($path[0] == "documents") {
                    $group = "list_document";
                } elseif ($path[0] == "contingencies") {
                    $group = "document_contingengy";
                } elseif (in_array($path[0], ["items", "brands", "item-sets"])) {
                    $group = "items";
                } elseif (in_array($path[0], ["categories"])) {
                    $group = "catalogs";
                } elseif (in_array($path[0], ["summaries", "voided"])) {
                    $group = "summary_voided";
                } elseif ($path[0] == "quotations") {
                    $group = "quotations";
                } elseif ($path[0] == "sale-notes") {
                    $group = "sale_notes";
                } elseif (in_array($path[0], ["incentives", "user-commissions"])) {
                    $group = "incentives";
                } elseif ($path[0] == "sale-opportunities") {
                    $group = "sale-opportunity";
                } elseif (in_array($path[0], ["contracts", "production-orders"])) {
                    $group = "contracts";
                } elseif ($path[0] == "order-notes") {
                    $group = "order-note";
                } elseif ($path[0] == "technical-services") {
                    $group = "technical-service";
                } elseif ($path[0] == "purchase-orders") {
                    $group = "purchases_orders";
                } elseif ($path[0] == "digemid") {
                    $group = "digemid";
                } else {
                    $group = null;
                }
                /** Configuracion Avanzada */
                if (
                    $firstLevel == "tasks" ||
                    $firstLevel == "offline-configurations" ||
                    $firstLevel == "series-configurations"
                ) {
                    $group = "configuration_advance";
                } /** Giro de negocio */
                elseif (
                    $firstLevel == "bussiness_turns" ||
                    $firstLevel == "advanced"
                ) {
                    $group = "configuration_company";
                } /** Giro de negocio */
                elseif ($firstLevel == "login-page") {
                    $group = "configuration_visual";
                }
            }
            return $group;
        }

        /**
         * @param Collection $levels
         * @param string     $group
         *
         * @return int
         */
        private function getLevelByGroup($levels, $group)
        {
            /** @var Collection $levels_x_group */
            $levels_x_group = $levels->filter(function ($module, $key) use ($group) {
                /** @var ModuleLevel $module */
                return $module->value === $group;
            });

            return $levels_x_group->count();
        }

        /**
         * Bajo ciertas circunstancias, $group se genera como new_document, este ajuste evalua el valor para nuevos
         * componentes.
         * configuration_advance
         * configuration_company
         * configuration_visual
         *
         * @param string $group
         * @param array  $path
         */
        private function fixPermissions(&$group, $path = [])
        {

            $firstLevel = $path[0] ?? null;
            $secondLevel = $path[1] ?? null;
            /** Configuracion avanzada */
            if (
                ($firstLevel == "company_accounts" && $secondLevel == 'create') ||
                ($firstLevel == "inventories" && $secondLevel == 'configuration') ||
                ($firstLevel == "configurations" && $secondLevel == 'sale-notes')
            ) {
                $group = "configuration_advance";
            } /** Giro de negocio */
            elseif (
                ($firstLevel == "companies" && $secondLevel == 'create')

            ) {
                $group = "configuration_company";
            } /** Configuracion Avanzada */
            elseif (
                $firstLevel == "tasks" ||
                $firstLevel == "offline-configurations" ||
                $firstLevel == "series-configurations"
            ) {
                $group = "configuration_advance";
            } /** Giro de negocio */
            elseif (
                $firstLevel == "bussiness_turns" ||
                $firstLevel == "advanced"
            ) {
                $group = "configuration_company";
            } /** Giro de negocio */
            elseif ($firstLevel == "login-page") {
                $group = "configuration_visual";
            } /** Suscripciones */
            elseif ($firstLevel == "suscription") {
                if ($secondLevel == 'client') {
                    $group = "suscription_app_client";
                } elseif ($secondLevel == 'service') {
                    $group = "suscription_app_service";
                } elseif ($secondLevel == 'payments') {
                    $group = "suscription_app_payments";
                } elseif ($secondLevel == 'plans') {
                    $group = "suscription_app_plans";
                }


            }

        }

        /**
         * @param $level
         *
         * @return RedirectResponse
         */
        private function redirectRoute($level)
        {

            switch ($level) {

                case 'new_document':
                    return redirect()->route('tenant.documents.create');

                case 'list_document':
                    return redirect()->route('tenant.documents.index');

                case 'document_not_sent':
                    return redirect()->route('tenant.documents.not_sent');

                case 'document_contingengy':
                    return redirect()->route('tenant.contingencies.index');

                case 'items':
                    return redirect()->route('tenant.items.index');

                case 'summary_voided':
                    return redirect()->route('tenant.summaries.create');

                case 'quotations':
                    return redirect()->route('tenant.quotations.create');

                case 'sale_notes':
                    return redirect()->route('tenant.sale_notes.create');

                case 'incentives':
                    return redirect()->route('tenant.incentives.create');


                case 'sale-opportunity':
                    return redirect()->route('tenant.sale_opportunities.index');

                case 'contracts':
                    return redirect()->route('tenant.contracts.create');

                case 'order-note':
                    return redirect()->route('tenant.order_notes.create');

                case 'technical-service':
                    return redirect()->route('tenant.technical_services.create');

                case 'purchases_orders':
                    return redirect()->route('tenant.purchase-orders.index');
                case 'digemid':
                    return redirect()->route('tenant.digemid.index');
                case 'configuration_visual':
                case 'configuration_advance':
                case 'configuration_company':
                    //'configuration_visual' 'configuration_advance' 'configuration_company' redirecciona a configuracion
                    return redirect()->route('tenant.general_configuration.index');

                case  "suscription_app_client":
                case  "suscription_app_service":
                case  "suscription_app_payments":
                case  "suscription_app_plans":
                return redirect()->route('tenant.suscription.client.index');
                    //return redirect()->route('tenant.suscription.service.index');
                    //return redirect()->route('tenant.suscription.payments.index');
                    //return redirect()->route('tenant.suscription.plans.index');
                default;
                    return redirect()->route('tenant.dashboard.index');


            }
        }

    }
