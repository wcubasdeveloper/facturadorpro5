<?php

    namespace App\Http\Controllers;

    use App\Models\Tenant\Catalogs\CatColorsItem;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ItemSupply;
    use App\Models\Tenant\ItemUnitType;
    use App\Models\Tenant\ItemWarehouse;
    use App\Models\Tenant\Warehouse;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use Modules\Inventory\Traits\InventoryTrait;

    /**
     * Tener en cuenta como base modules/Document/Traits/SearchTrait.php
     * Class SearchItemController
     *
     * @package App\Http\Controllers
     * @mixin Controller
     */
    class SearchItemController extends Controller
    {

        // use InventoryTrait;

        /**
         * Devuelve una lista de items unido entre service y no service.
         *
         * @param Request|null $request
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getAllItem(Request $request = null)
        {

            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            self::validateRequest($request);
            $notService = self::getNotServiceItem($request);
            $Service = self::getServiceItem($request);
            $notService->merge($Service);
            return $notService->transform(function ($row) use ($warehouse) {
                /** @var Item $row */

                return $row->getDataToItemModal($warehouse);
            });
        }

        /**
         * @param Request|null $request
         */
        protected static function validateRequest(&$request)
        {
            if ($request == null) $request = new Request();

        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public static function getNotServiceItem(Request $request = null, $id = 0)
        {

            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);
            $item = self::getAllItemBase($request, false, $id);

            // el filtro por almacén no debe depender de la búsqueda por código de barras o coincidencias
            // if ($search_by_barcode === false && $input != null) {
            //     self::SetWarehouseToUser($item);
            // }

            self::SetWarehouseToUser($item);

            return $item->orderBy('description')->get();
        }

        
        /**
         * 
         * No aplica filtro por almacén
         * 
         * @param Request|null $request
         * @param int          $id
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public static function getNotServiceItemWithOutWarehouse(Request $request = null, $id = 0)
        {

            self::validateRequest($request);
            // $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            // $input = self::setInputByRequest($request);
            $item = self::getAllItemBase($request, false, $id);

            return $item->orderBy('description')->get();
        }

        /**
         * Busca la propiedad input o input_item para generar busquedas
         *
         * @param Request|null $request
         *
         * @return mixed|null
         */
        protected static function setInputByRequest(Request $request = null)
        {
            if (!empty($request)) {
                $input = ($request->has('input')) ? $request->input : null;
                if (empty($input) && $request->has('input_item')) {
                    $input = ($request->has('input_item')) ? $request->input_item : null;
                }
            }
            return $input;
        }

        /**
         * @param Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function getAllItemBase(Request $request = null, $service = false, $id = 0)
        {

            self::validateRequest($request);
            $search_item_by_series = Configuration::first()->isSearchItemBySeries();
            $production = (bool)($request->production ??false);

            $items_id = ($request->has('items_id')) ? $request->items_id : null;
            $id = (int)$id;
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);
            $search_item_by_barcode_presentation = $request->has('search_item_by_barcode_presentation') && (bool)$request->search_item_by_barcode_presentation;

            // $item = Item:: whereIsActive();
            $item = Item::query();
            $ItemToSearchBySeries = Item:: whereIsActive();

            if ($service == false) {
                $item->WhereNotService();
                $ItemToSearchBySeries->WhereNotService();
            } else {
                $item->WhereService()
                    ->whereNotIsSet();
                $ItemToSearchBySeries->WhereService()
                    ->whereNotIsSet();
            }


            if($production !== false) {
                // busqueda de insumos, no se lista por codigo de barra o por series
                $search_item_by_series = false;
            } else {
                $item->with('warehousePrices');
                $ItemToSearchBySeries->with('warehousePrices');
            }

            $alt_item = $item;

            $bySerie = null;
            if ($search_item_by_series == true) {
                self::validateRequest($request);
                $warehouse = Warehouse::select('id')->where('establishment_id', auth()->user()->establishment_id)->first();
                $input = self::setInputByRequest($request);
                if (!empty($input)) {

                    $ItemToSearchBySeries->WhereHas('item_lots', function ($query) use ($warehouse, $input) {
                        $query->where('has_sale', false);
                        $query->where('warehouse_id', $warehouse->id);
                        $query->where('series', $input);
                        // return $query;
                    })->take(1);

                    //Busca el item con relacion al almacen
                    self::SetWarehouseToUser($item);
                    self::SetWarehouseToUser($ItemToSearchBySeries);
                    $bySerie = $ItemToSearchBySeries->first();
                    if ($bySerie !== null) {
                        //Si existe un dato, devuelve la busqueda por serie.
                        $item->WhereHas('item_lots', function ($query) use ($warehouse, $input) {
                            $query->where('has_sale', false);
                            $query->where('warehouse_id', $warehouse->id);
                            $query->where('series', $input);
                        })->take(1);


                    }
                }
            }


            if ($bySerie === null) {
                if ($items_id != null) {
                    $item->whereIn('id', $items_id);
                } elseif ($id != 0) {
                    $item->where('id', $id);
                } else {
                    if ($search_by_barcode === true) {

                        if($search_item_by_barcode_presentation)
                        {
                            $item->filterItemUnitTypeBarcode($input)->limit(1);
                        }
                        else
                        {
                            $item
                                ->where('barcode', $input)
                                ->limit(1);
                        }

                    } else {
                        self::setFilter($item, $request);
                    }
                }
            }

            return $item->whereIsActive()->orderBy('description');
        }

        /**
         * Establece que solo se mostraria los item donde el usuario se encuentra
         *
         * @param $item
         */
        public static function SetWarehouseToUser(&$item)
        {
            /** @var Item $item */
            // En este caso, se desestima esta configuracion ya que debe filtrase por el almacen del usuario
            // dejando sin efecto por el issue #1046
         //   $configuration =  Configuration::first()-> isShowItemsOnlyUserStablishment();
         //   if($configuration == true) {
                $item->whereWarehouse();
         //   }

        }

        /**
         * @param              $item
         * @param Request|null $request
         */
        protected static function setFilter(&$item, Request $request = null)
        {
            /** @var Builder $item */

            $input = self::setInputByRequest($request);

            if (!empty($input)) {
                $whereItem[] = ['description', 'like', '%' . str_replace(' ','%',$input) . '%'];
                $whereItem[] = ['internal_id', 'like', '%' . $input . '%'];
                $whereItem[] = ['barcode', '=', $input];
                $whereExtra[] = ['name', 'like', '%' .  str_replace(' ','%',$input) . '%'];

                foreach ($whereItem as $index => $wItem) {
                    if ($index < 1) {
                        $item->Where([$wItem]);
                    } else {
                        $item->orWhere([$wItem]);
                    }
                }

                if (!empty($whereExtra)) {
                    $item
                        ->orWhereHas('brand', function ($query) use ($whereExtra) {
                            $query->where($whereExtra);
                        })
                        ->orWhereHas('category', function ($query) use ($whereExtra) {
                            $query->where($whereExtra);
                        });
                }
                $item->OrWhereJsonContains('attributes', ['value' => $input]);
                //  Limita los resultados de busqueda, inicial 250, puede modificarse en el .env con NUMBER_SEARCH_ITEMS
                $item->take(\Config('extra.number_items_in_search'));

            }else{
                // Si no se filtran datos, entonces se toman 20, puede añadirse en el env la variable NUMBER_ITEMS
                $item->take(\Config('extra.number_items_at_start'));
            }


        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getServiceItem(Request $request = null, $id = 0)
        {
            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);
            /** @var Item $item */
            $item = self::getAllItemBase($request, true, $id);

            if ($search_by_barcode === false && $input != null) {
                self::SetWarehouseToUser($item);
            }


            return $item->orderBy('description')->get();

        }

        /**
         * @param Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function getNotServiceItemToModal(Request $request = null, $id = 0)
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            self::validateRequest($request);
            return self::getNotServiceItem($request, $id)->transform(function ($row) use ($warehouse) {
                /** @var Item $row */

                return $row->getDataToItemModal($warehouse);
            });
        }

        /**
         * Reaqliza una busqueda de item por id, Intenta por item, luego por servicio
         * Devuelve un standar de modal
         *
         * @param int $id
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function searchByIdToModal($id = 0)
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            $items = self::searchById($id)->transform(function ($row) use ($warehouse) {
                /** @var Item $row */
                return $row->getDataToItemModal(
                    $warehouse,
                    true,
                    null,
                    false,
                    true
                );

            });
            return $items;
        }

        /**
         * @param int $id
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function searchById($id = 0)
        {
            $search_item = self::getNotServiceItem(null, $id);
            if (count($search_item) == 0) {
                $search_item = self::getServiceItem(null, $id);

            }
            return $search_item;
        }

        /**
         * @param Request $request
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function searchByRequest(Request $request)
        {
            $search_item = self::getNotServiceItem($request);
            if (count($search_item) == 0) {
                $search_item = self::getServiceItem($request);

            }
            return $search_item;
        }

        /**
         * @param int $id
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function searchByIdToPurchase($id = 0)
        {
            $search_item = self::getNotServiceItemToPurchase(null, $id);
            if (count($search_item) == 0) {
                $search_item = self::getServiceItemToPurchase(null, $id);

            }
            return $search_item;
        }

        /**
         * Devuelve el conjunto para ventas sin los pack o productos compuestos
         *
         * @param Request|null $request
         * @param int          $id
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public static function getNotServiceItemToPurchase(Request $request = null, $id = 0)
        {

            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);

            $item = self::getAllItemBase($request, false, $id);

            $item->WhereNotIsSet();


            if ($search_by_barcode === false && $input != null) {
                self::SetWarehouseToUser($item);
            }


            return $item->orderBy('description')->get();
        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getServiceItemToPurchase(Request $request = null, $id = 0)
        {
            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);
            /** @var Item $item */
            $item = self::getAllItemBase($request, true, $id);
            $item->WhereNotIsSet();

            if ($search_by_barcode === false && $input != null) {
                self::SetWarehouseToUser($item);
            }


            return $item->orderBy('description')->get();

        }

        /**
         * @param Request $request
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function searchByRequestToPurchase(Request $request)
        {
            $search_item = self::getNotServiceItemToPurchase($request);
            if (count($search_item) == 0) {
                $search_item = self::getServiceItemToPurchase($request);

            }
            return $search_item;
        }


        /**
         * Retorna la coleccion de items par Documento y Boleta.
         *  Usado en app/Http/Controllers/Tenant/DocumentController.php::250
         *  Usado en app/Http/Controllers/Tenant/DocumentController.php::370
         *  Usado en modules/Document/Http/Controllers/DocumentController.php::297
         *
         * @param Request| null $request
         * @param int           $id
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function getItemsToSupply(Request $request = null, $id = 0)
        {

            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = self::setInputByRequest($request);
            $item = self::getAllItemBase($request, false, $id);

            /*
            if ($search_by_barcode === false && $input != null) {
                self::SetWarehouseToUser($item);
            }
            */
             $item->ForProductionSupply();
             // $item->wherein('id',ItemSupply::select('individual_item_id')->pluck('individual_item_id'));
            return self::TransformToModalAndSupply($item->orderBy('description')->get());

        }


        /**
         * Retorna la coleccion de items par Documento y Boleta.
         *  Usado en app/Http/Controllers/Tenant/DocumentController.php::250
         *  Usado en app/Http/Controllers/Tenant/DocumentController.php::370
         *  Usado en modules/Document/Http/Controllers/DocumentController.php::297
         *
         * @param Request| null $request
         * @param int           $id
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function getItemsToDocuments(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            return self::TransformToModal($items_not_services->merge($items_services));


            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            // $items_u = Item::whereWarehouse()->whereIsActive()->whereNotIsSet()->orderBy('description')->take(20)->get();
            $item_not_service = Item::with('warehousePrices')
                ->whereIsActive()
            ->orderBy('description');
            $service_item = Item::with('warehousePrices')
                ->where('items.unit_type_id', 'ZZ')
                ->whereIsActive()
                ->orderBy('description');
            $item_not_service = $item_not_service
                // Configurable en  env la variable NUMBER_ITEMS
                ->take(\Config('extra.number_items_at_start'))
                ->get();
            $service_item = $service_item
                // Configurable en  env la variable NUMBER_ITEMS
                ->take(\Config('extra.number_items_at_start'))
                //->take(10)
                ->get();
            return self::TransformToModal($item_not_service->merge($service_item));
        }

        /**
         * @param Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed $items
         * @param Warehouse|null                                                                                                     $warehouse
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function TransformToModal($items, Warehouse $warehouse = null)
        {
            /** @var Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed $items */
            return $items
                ->transform(function ($row) use ($warehouse) {
                    /** @var Item $row */
                    return $row->getDataToItemModal($warehouse);
                });

        }
        /**
         * @param Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed $items
         * @param Warehouse|null                                                                                                     $warehouse
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function TransformToModalAndSupply($items, Warehouse $warehouse = null)
        {
            /** @var Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed $items */
            return $items
                ->transform(function (Item $row) use ($warehouse) {
                    $data= $row->getDataToItemModal($warehouse);
                    $suppl = $row->supplies;


                    $data['supplies'] = $row->supplies;
                    return  $data;
                });

        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return \Illuminate\Database\Eloquent\Collection
         */
        public static function getItemsToSaleNote(Request $request = null, $id = 0)
        {

            /*

            $items_u = Item::whereWarehouse()->whereIsActive()->whereNotIsSet()->orderBy('description')->take(20)->get();

            $items_s = Item::where('unit_type_id','ZZ')->whereIsActive()->orderBy('description')->take(10)->get();

            $items = $items_u->merge($items_s);
            */

            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);

            return self::TransformToModalSaleNote($items_not_services->merge($items_services));

        }

        /**
         * @param Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed $items
         * @param Warehouse|null                                                                                                     $warehouse
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function TransformToModalSaleNote($items, Warehouse $warehouse = null)
        {
            $warehouse_id = ($warehouse) ? $warehouse->id : null;
            if ($warehouse_id == null) {
                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id : null;
            }

            return $items->transform(function ($row) use ($warehouse_id, $warehouse) {
                /** @var Item $row */

                $temp =   [
                    'id' => $row->id,
                    'sale_unit_price' => round($row->sale_unit_price, 6),
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'has_igv' => (bool)$row->has_igv,
                    'lots_enabled' => (bool)$row->lots_enabled,
                    'series_enabled' => (bool)$row->series_enabled,
                    'is_set' => (bool)$row->is_set,
                    'warehouses' => collect($row->warehouses)->transform(function ($row) use ($warehouse_id) {
                        /** @var ItemWarehouse $row */
                        /** @var Warehouse $c_warehouse */
                        $c_warehouse = $row->warehouse;

                        return [
                            'warehouse_id' => $c_warehouse->id,
                            'warehouse_description' => $c_warehouse->description,
                            'stock' => $row->stock,
                            'checked' => ($c_warehouse->id == $warehouse_id) ? true : false,
                        ];
                    }),
                    'item_unit_types' => $row->item_unit_types,
                    'lots' => [],
                    'lots_group' => collect($row->lots_group)->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'code' => $row->code,
                            'quantity' => $row->quantity,
                            'date_of_due' => $row->date_of_due,
                            'checked' => false,
                            'compromise_quantity' => 0

                        ];
                    }),
                    'lot_code' => $row->lot_code,
                    'date_of_due' => $row->date_of_due,
                ];

                return  array_merge($row->getCollectionData(), $row->getDataToItemModal(),$temp);
            });

        }

        /**
         * Centralizado de busqueda para Cotizaciones
         *
         * @param Request|null $request
         * @param int          $id
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function getItemsToQuotation(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);

            $onlyService = false;
            if(
                ($request !== null  && $request->has('only_service') && (bool)$request->only_service == true) ||
                (isset($_GET['only_service']) && $_GET['only_service'] == 1)
            ){
            // Si la busqueda tiene only_service DEBE BUSCAR SOLO SERVICIOS
                $onlyService = true;
            }

            if($onlyService == true) {
                return self::TransformToModal($items_services);

            }
            return self::TransformToModal($items_not_services->merge($items_services));
        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return mixed
         */
        public static function getItemsToOrderNote(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            return self::TransformModalToOrderNote($items_not_services->merge($items_services), $warehouse);
        }

        /**
         * @param                $items
         * @param Warehouse|null $warehouse
         *
         * @return mixed
         */
        public static function TransformModalToOrderNote($items, Warehouse $warehouse = null)
        {
            $warehouse_id = ($warehouse) ? $warehouse->id : null;

            if ($warehouse_id == null) {
                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id : null;
            }
            return $items->transform(function ($row) use ($warehouse_id, $warehouse) {
                /** @var Item $row */
                $detail = self::getFullDescriptionToSaleNote($row, $warehouse);
                return [
                    'id' => $row->id,
                    'full_description' => $detail['full_description'],
                    'brand' => $detail['brand'],
                    'category' => $detail['category'],
                    'stock' => $detail['stock'],
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => round($row->sale_unit_price, 2),
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'has_igv' => (bool)$row->has_igv,
                    'lots_enabled' => (bool)$row->lots_enabled,
                    'series_enabled' => (bool)$row->series_enabled,
                    'is_set' => (bool)$row->is_set,
                    'warehouses' => collect($row->warehouses)->transform(function ($row) use ($warehouse) {
                        return [
                            'warehouse_id' => $row->warehouse->id,
                            'warehouse_description' => $row->warehouse->description,
                            'stock' => $row->stock,
                            'checked' => ($row->warehouse_id == $warehouse->id) ? true : false,
                        ];
                    }),
                    'item_unit_types' => $row->item_unit_types,
                    'lots' => [],
                    'lots_group' => collect($row->lots_group)->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'code' => $row->code,
                            'quantity' => $row->quantity,
                            'date_of_due' => $row->date_of_due,
                            'checked' => false,
                            'compromise_quantity' => 0
                        ];
                    }),
                    'lot_code' => $row->lot_code,
                    'date_of_due' => $row->date_of_due
                ];
            });
        }

        /**
         * @param Item           $item
         * @param Warehouse|null $warehouse
         *
         * @return string[]
         */
        public static function getFullDescriptionToSaleNote(Item $item, Warehouse $warehouse = null)
        {

            $desc = ($item->internal_id) ? $item->internal_id . ' - ' . $item->description : $item->description;
            $category = ($item->category) ? "{$item->category->name}" : "";
            $brand = ($item->brand) ? "{$item->brand->name}" : "";

            if ($item->unit_type_id != 'ZZ') {
                $warehouse_stock = ($item->warehouses && $warehouse) ? number_format($item->warehouses->where('warehouse_id', $warehouse->id)->first() != null ? $item->warehouses->where('warehouse_id', $warehouse->id)->first()->stock : 0, 2) : 0;
                $stock = ($item->warehouses && $warehouse) ? "{$warehouse_stock}" : "";
            } else {
                $stock = '';
            }


            $desc = "{$desc} - {$brand}";

            return [
                'full_description' => $desc,
                'brand' => $brand,
                'category' => $category,
                'stock' => $stock,
            ];
        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return mixed
         */
        public static function getItemToPurchaseOrder(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            return self::TransformModalToPurchaseOrder($items_not_services->merge($items_services), $warehouse);
            //
        }

        /**
         * @param                $items
         * @param Warehouse|null $warehouse
         *
         * @return mixed
         */
        public static function TransformModalToPurchaseOrder($items, Warehouse $warehouse = null)
        {
            $warehouse_id = ($warehouse) ? $warehouse->id : null;

            if ($warehouse_id == null) {
                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id : null;
            }
            return $items->transform(function ($row) use ($warehouse_id, $warehouse) {
                /** @var Item $row */
                $full_description = self::getFullDescriptionToPurchaseOrder($row);
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'model' => $row->model,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'has_perception' => (bool)$row->has_perception,
                    'purchase_has_igv' => (bool)$row->purchase_has_igv,
                    'percentage_perception' => $row->percentage_perception,
                    'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => "{$row->description}",
                            'item_id' => $row->item_id,
                            'unit_type_id' => $row->unit_type_id,
                            'quantity_unit' => $row->quantity_unit,
                            'price1' => $row->price1,
                            'price2' => $row->price2,
                            'price3' => $row->price3,
                            'price_default' => $row->price_default,
                        ];
                    }),
                    'series_enabled' => (bool)$row->series_enabled,
                ];
            });
        }

        /**
         * @param Item $item
         *
         * @return string
         */
        public static function getFullDescriptionToPurchaseOrder(Item $item)
        {

            $desc = ($item->internal_id) ? $item->internal_id . ' - ' . $item->description : $item->description;
            $category = ($item->category) ? " - {$item->category->name}" : "";
            $brand = ($item->brand) ? " - {$item->brand->name}" : "";

            $desc = "{$desc} {$category} {$brand}";

            return $desc;
        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return mixed
         */
        public static function getItemToPurchaseQuotation(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            return self::TransformModalToPurchaseQuotation($items_not_services->merge($items_services), $warehouse);
            //
        }

        /**
         * @param                $items
         * @param Warehouse|null $warehouse
         *
         * @return mixed
         */
        public static function TransformModalToPurchaseQuotation($items, Warehouse $warehouse = null)
        {
            $warehouse_id = ($warehouse) ? $warehouse->id : null;

            if ($warehouse_id == null) {
                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id : null;
            }
            return $items->transform(function ($row) use ($warehouse_id, $warehouse) {
                /** @var Item $row */
                $full_description = self::getFullDescriptionToPurchaseQuotation($row);
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'unit_type_id' => $row->unit_type_id,
                    'is_set' => (bool)$row->is_set,
                    'model' => $row->model,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'has_perception' => (bool)$row->has_perception,
                    'purchase_has_igv' => (bool)$row->purchase_has_igv,
                    'percentage_perception' => $row->percentage_perception,
                    'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => "{$row->description}",
                            'item_id' => $row->item_id,
                            'unit_type_id' => $row->unit_type_id,
                            'quantity_unit' => $row->quantity_unit,
                            'price1' => $row->price1,
                            'price2' => $row->price2,
                            'price3' => $row->price3,
                            'price_default' => $row->price_default,
                        ];
                    }),
                    'series_enabled' => (bool)$row->series_enabled,
                ];
            });
        }

        /**
         * @param Item $item
         *
         * @return string
         */
        public static function getFullDescriptionToPurchaseQuotation($item)
        {
            /** @var Item $item */

            $desc = ($item->internal_id) ? $item->internal_id . ' - ' . $item->description : $item->description;
            $category = ($item->category) ? " - {$item->category->name}" : "";
            $brand = ($item->brand) ? " - {$item->brand->name}" : "";

            $desc = "{$desc} {$category} {$brand}";

            return $desc;
        }

        /**
         * @param Request|null $request
         * @param int          $id
         *
         * @return mixed
         */
        public static function getItemToPurchase(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItemWithOutWarehouse($request, $id);
            // $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

            return self::TransformModalToPurchase($items_not_services->merge($items_services), $warehouse);
            //
        }

        public static function TransformModalToPurchase($items, Warehouse $warehouse = null)
        {
            $warehouse_id = ($warehouse) ? $warehouse->id : null;

            if ($warehouse_id == null) {
                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id : null;
            }
            return $items->transform(function ($row) use ($warehouse_id, $warehouse) {
                /** @var Item $row */
                $temp = array_merge($row->getCollectionData(), $row->getDataToItemModal());
                $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                $data = [
                    'id' => $row->id,
                    'item_code' => $row->item_code,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'purchase_has_igv' => (bool)$row->purchase_has_igv,
                    'has_perception' => (bool)$row->has_perception,
                    'lots_enabled' => (bool)$row->lots_enabled,
                    'percentage_perception' => $row->percentage_perception,
                    // 'warehouses' => collect($row->warehouses)->transform(function($row) {
                    //     return [
                    //         'warehouse_id' => $row->warehouse->id,
                    //         'warehouse_description' => $row->warehouse->description,
                    //         'stock' => $row->stock,
                    //     ];
                    // }) [
                    'item_unit_types' => $row->item_unit_types->transform(function ($row) {
                        if (is_array($row)) return $row;
                        if (is_object($row)) {
                            /**@var ItemUnitType $row */
                            return $row->getCollectionData();
                        }
                        return $row;
                    }),
                    'series_enabled' => (bool)$row->series_enabled,
                    
                    'purchase_has_isc' => $row->purchase_has_isc,
                    'purchase_system_isc_type_id' => $row->purchase_system_isc_type_id,
                    'purchase_percentage_isc' => $row->purchase_percentage_isc,

                ];
                foreach ($temp as $k => $v) {
                    if (!isset($data[$k])) {
                        $data[$k] = $v;
                    }
                }
                return $data;
            });
        }

        /**
         * @param Request $request
         * @param int     $id
         *
         * @return mixed
         */
        public static function getItemToContract(Request $request = null, $id = 0)
        {
            // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

            /*
            $items = Item::orderBy('description')->whereIsActive()
                // ->with(['warehouses' => function($query) use($warehouse){
                //     return $query->where('warehouse_id', $warehouse->id);
                // }])
                ->get();
*/
            $items_not_services = self::getNotServiceItem($request, $id);
            $items_services = self::getServiceItem($request, $id);
            // $establishment_id = auth()->user()->establishment_id;
            $items = $items_not_services->merge($items_services);

            return self::TransformModalToContract($items);
        }

        /**
         * @param                $items
         * @param Warehouse|null $warehouse
         *
         * @return mixed
         */
        public static function TransformModalToContract($items, Warehouse $warehouse = null)
        {
            return $items->transform(function ($row) use ($warehouse) {
                $full_description = self::getFullDescriptionToContract($row);
                // $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'is_set' => (bool)$row->is_set,
                    'has_igv' => (bool)$row->has_igv,
                    'calculate_quantity' => (bool)$row->calculate_quantity,
                    'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => "{$row->description}",
                            'item_id' => $row->item_id,
                            'unit_type_id' => $row->unit_type_id,
                            'quantity_unit' => $row->quantity_unit,
                            'price1' => $row->price1,
                            'price2' => $row->price2,
                            'price3' => $row->price3,
                            'price_default' => $row->price_default,
                        ];
                    }),
                    'warehouses' => collect($row->warehouses)->transform(function ($row) {
                        return [
                            'warehouse_id' => $row->warehouse->id,
                            'warehouse_description' => $row->warehouse->description,
                            'stock' => $row->stock,
                        ];
                    })
                ];
            });
        }

        /**
         * @param Item $item
         *
         * @return string
         */
        public static function getFullDescriptionToContract(Item $item)
        {

            $desc = ($item->internal_id) ? $item->internal_id . ' - ' . $item->description : $item->description;
            $category = ($item->category) ? " - {$item->category->name}" : "";
            $brand = ($item->brand) ? " - {$item->brand->name}" : "";

            $desc = "{$desc} {$category} {$brand}";

            return $desc;
        }

        /**
         * @param \Illuminate\Http\Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Collection
         */
        public static function getItemToTrasferWithSearch(Request $request = null): \Illuminate\Database\Eloquent\Collection
        {
            $warehouse_id = 0;
            $whereItem = [];
            $whereExtra = [];
            if($request !== null && $request->has('params')){
                $params = $request->params;
                $warehouse_id = $params['warehouse_id'];
                $input = $params['input'];
                $search_by_barcode = $params['search_by_barcode'];
            }


            $data = self::getItemToTrasferCollection($warehouse_id);


            if(!empty($input)) {
                $whereItem[] = ['description', 'like', '%' . $input . '%'];
                $whereItem[] = ['internal_id', 'like', '%' . $input . '%'];
                $whereItem[] = ['barcode', '=', $input];
                $whereExtra[] = ['name', 'like', '%' . $input . '%'];
            }

            if(!empty($whereItem)) {
                foreach ($whereItem as $index => $wItem) {
                    if ($index < 1) {
                        $data->Where([$wItem]);
                    } else {
                        $data->orWhere([$wItem]);
                    }
                }

                if ( !empty($whereExtra)) {
                    $data
                        ->orWhereHas('brand', function ($query) use ($whereExtra) {
                            $query->where($whereExtra);
                        })
                        ->orWhereHas('category', function ($query) use ($whereExtra) {
                            $query->where($whereExtra);
                        });
                }
                $data->OrWhereJsonContains('attributes', ['value' => $input]);
                // Limita la cantidad de productos en la busqueda a 250, puede modificarse en el .env con NUMBER_SEARCH_ITEMS
                $data->take(\Config('extra.number_items_in_search'));
            }else{
                // Inicia con 20 productos, puede añadirse en el env la variable NUMBER_ITEMS
                $data->take(\Config('extra.number_items_at_start'));

            }

            $data->whereIsActive();

            return self::getItemToTrasferModal($data,$warehouse_id);
        }

        /**
         * @param int $warehouse_id
         *
         * @return \Illuminate\Database\Eloquent\Collection
         */
        public static function getItemToTrasferWithoutSearch( $warehouse_id = 0): \Illuminate\Database\Eloquent\Collection
        {
            $data = self::getItemToTrasferCollection($warehouse_id)->whereIsActive();

            // Inicia con 20 productos, puede añadirse en el env la variable NUMBER_ITEMS
            $data->take(\Config('extra.number_items_at_start'));
            return  self::getItemToTrasferModal($data,$warehouse_id);
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder|null $data
         * @param int $warehouse_id
         *
         * @return \Illuminate\Database\Eloquent\Collection
         */
        public static function getItemToTrasferModal(
            \Illuminate\Database\Eloquent\Builder $data = null,
            int $warehouse_id = 0
        ): \Illuminate\Database\Eloquent\Collection
        {
            return $data
                ->get()
                ->transform(function ($row) use ($warehouse_id) {
                    /** @var \App\Models\Tenant\Item $row */
                    $lots = $row->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse_id)->transform(function ($row1) {
                        return [
                            'id' => $row1->id,
                            'series' => $row1->series,
                            'date' => $row1->date,
                            'item_id' => $row1->item_id,
                            'warehouse_id' => $row1->warehouse_id,
                            'has_sale' => (bool)$row1->has_sale,
                            'lot_code' => ($row1->item_loteable_type) ? (isset($row1->item_loteable->lot_code) ? $row1->item_loteable->lot_code : null) : null
                        ];
                    })->values();
                    $old = [
                        'lots' => $lots,
                    ];
                    $data = $row->getDataToItemModal(
                        Warehouse::find($warehouse_id),
                        false,
                        true

                    );
                    return array_merge($data, $old);
                });

        }

        /**
         * Realiza las busquedas para transferencia de items
         *
         * Extraido de modules/Inventory/Traits/InventoryTrait.php  optionsItemWareHousexId
         *
         * @param int $warehouse_id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function getItemToTrasferCollection(
            $warehouse_id = 0
        ):\Illuminate\Database\Eloquent\Builder
        {

            return Item::query()
                ->with('item_lots', 'warehouses')
                ->whereHas('warehouses', function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                })
                ->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']])
                ->whereNotIsSet();
        }

        public static function getItemsToPackageZone(Request $request = null, $id = 0)
        {
            $items_not_services = self::getNotServiceItem($request, $id);
            // $items_services = self::getServiceItem($request, $id);
            // $data = self::TransformToModal($items_not_services->merge($items_services));
            $data = self::TransformToModal($items_not_services);
            return $data->transform(function ($row) {
                $data = $row;
                $data['color'] = CatColorsItem::wherein('id', $row['colors'])->get();
                return $data;
            });

        }
    }
