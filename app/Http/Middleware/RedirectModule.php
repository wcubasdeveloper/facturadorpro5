<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;

    /**
     * Class RedirectModule
     *
     * @package App\Http\Middleware
     */
    class RedirectModule
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

            $module = $request->user()->getModule();
            $path = explode('/', $request->path());
            $modules = $request->user()->getModules();

            if (!$request->ajax()) {

                if (count($modules)) {
                    // if(count($modules) < 15){

                    $group = $this->getGroup($path, $module);

                    if ($group) {
                        if ($this->getModuleByGroup($modules, $group) === 0) {
                            return $this->redirectRoute($module);
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
         * @return string
         */
        private function getGroup($path, $module)
        {

            $firstLevel = $path[0] ?? null;
            $secondLevel = $path[1] ?? null;
            $group = null;
            ///* Module Documents */
            if (
                $firstLevel == "documents" ||
                $firstLevel == "dashboard" ||
                $firstLevel == "quotations" ||
                $firstLevel == "items" ||
                $firstLevel == "summaries" ||
                $firstLevel == "voided") {
                $group = "documents";
            } ///* Module purchases  */
            elseif (
                $firstLevel == "purchases" ||
                $firstLevel == "expenses") {
                $group = "purchases";
            } ///* Module advanced */
            elseif (
                $firstLevel == "retentions" ||
                $firstLevel == "dispatches" ||
                $firstLevel == "perceptions") {
                $group = "advanced";
            } ///* Module reports */
            elseif (
                $firstLevel == "list-reports" ||
                ($firstLevel == "reports" && $secondLevel == "purchases") ||
                ($firstLevel == "reports" && $secondLevel == "sales") ||
                ($firstLevel == "reports" && $secondLevel == "consistency-documents")) {
                $group = "reports";
            } // cuenta / listado de pagos
            elseif (
                $firstLevel == "cuenta") {
                $group = "cuenta";
            } ///* Module configuration */
            elseif (
                $firstLevel == "users" ||
                $firstLevel == "establishments") {
                $group = "establishments";
                // $group = "configuration";
            }//
            elseif (
                $firstLevel == "companies") {
                $group = "configuration";
                if (count($path) > 0 && $secondLevel == "uploads" && $module == "documents") {
                    $group = "documents";
                }
            }//
            elseif (
                $firstLevel == "catalogs" ||
                $firstLevel == "advanced") {
                $group = "configuration";
            } ///* Determinate type person */
            elseif (
                $firstLevel == "persons") {
                if ($secondLevel == "suppliers") {
                    $group = "purchases";
                }//
                elseif ($secondLevel == "customers") {
                    $group = "persons";
                } else {
                    $group = null;
                }
            }//
            elseif (
                $firstLevel == "person-types") {
                $group = "persons";
            } ///* Module pos */
            elseif (
                $firstLevel == "pos" ||
                $firstLevel == "cash") {
                $group = "pos";
            } ///* Module inventory */
            elseif (
                $firstLevel == "warehouses"||
                $firstLevel == "inventory" ||
                ($firstLevel == "reports" && $secondLevel == "kardex") ||
                ($firstLevel == "reports" && $secondLevel == "inventory")) {
                $group = "inventory";
            } ///* Module accounting */
            elseif (
                $firstLevel == "account") {
                $group = "accounting";
            } ///* Module finance */
            elseif (
                $firstLevel == "finances") {
                $group = "finance";
            }//
            elseif (
                $firstLevel == "orders" ||
                ($firstLevel == "ecommerce" && $secondLevel == "configuration") ||
                $firstLevel == "items_ecommerce" ||
                $firstLevel == "tags" ||
                $firstLevel == "promotions") {
                $group = "ecommerce";
            }//
            elseif (
                $firstLevel == "hotels" ||
                ($firstLevel == "hotels" && $secondLevel == "document-hotels")) {
                $group = "hotels";
            }//
            elseif (
                $firstLevel == "documentary-procedure") {
                $group = "documentary-procedure";
            }//
            elseif (
                $firstLevel == "digemid") {
                $group = "digemid";
            }//
            elseif (
                $firstLevel == "suscription") {
                $group = "suscription_app";
            }

            return $group;
        }

        /**
         * @param $modules
         * @param $group
         *
         * @return mixed
         */
        private function getModuleByGroup($modules, $group)
        {

            $modules_x_group = $modules->filter(function ($module, $key) use ($group) {
                return $module->value === $group;
            });

            return $modules_x_group->count();
        }

        /**
         * @param $module
         *
         * @return RedirectResponse
         */
        private function redirectRoute($module)
        {

            switch ($module) {

                case 'pos':
                    return redirect()->route('tenant.pos.index');

                case 'documents':
                    return redirect()->route('tenant.documents.create');

                case 'purchases':
                    return redirect()->route('tenant.purchases.index');

                case 'advanced':
                    return redirect()->route('tenant.retentions.index');

                case 'reports':
                    return redirect()->route('tenant.reports.purchases.index');

                case 'configuration':
                    return redirect()->route('tenant.companies.create');

                case 'inventory':
                    return redirect()->route('warehouses.index');

                case 'accounting':
                    return redirect()->route('tenant.account.index');

                case 'finance':
                    return redirect()->route('tenant.finances.global_payments.index');

                case 'establishments':
                    return redirect()->route('tenant.users.index');

                case 'documentary-procedure':
                case 'hotels':
                    return redirect()->route('tenant.hotels.index');
                case 'digemid':
                    return redirect()->route('tenant.digemid.index');
                case 'suscription_app':
                    return redirect()->route('tenant.suscription.client.index');

                default;
                    return redirect()->route('tenant.dashboard.index');
                /*case 'ecommerce':
                    return redirect()->route('tenant.ecommerce.index');*/

            }
        }

    }
