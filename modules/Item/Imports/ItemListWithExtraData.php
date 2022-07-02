<?php

    namespace Modules\Item\Imports;

    use App\Models\Tenant\Catalogs\CatColorsItem;
    use App\Models\Tenant\Catalogs\CatItemMoldCavity;
    use App\Models\Tenant\Catalogs\CatItemMoldProperty;
    use App\Models\Tenant\Catalogs\CatItemPackageMeasurement;
    use App\Models\Tenant\Catalogs\CatItemProductFamily;
    use App\Models\Tenant\Catalogs\CatItemStatus;
    use App\Models\Tenant\Catalogs\CatItemUnitBusiness;
    use App\Models\Tenant\Catalogs\CatItemUnitsPerPackage;
    use App\Models\Tenant\CatItemSize;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ItemColor;
    use App\Models\Tenant\ItemMoldCavity;
    use App\Models\Tenant\ItemMoldProperty;
    use App\Models\Tenant\ItemPackageMeasurement;
    use App\Models\Tenant\ItemProductFamily;
    use App\Models\Tenant\ItemSize;
    use App\Models\Tenant\ItemStatus;
    use App\Models\Tenant\ItemUnitBusiness;
    use App\Models\Tenant\ItemUnitsPerPackage;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Collection;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;


    class ItemListWithExtraData implements ToCollection
    {
        use Importable;

        protected $data;

        public function collection(Collection $rows)
        {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);

            foreach ($rows as $row) {


                $internal_id = $row[0] ?? ''; //  "Código Interno (Producto)"
                $description = $row[1] ?? ''; //  Descripcion - no se usa
                $catItemUnitBusiness = $row[2] ?? null;  //Unidad de Negocio
                $catItemUnitsPerPackage = $row[3] ?? null;  //Unides por Paquete
                $catItemPackageMeasurements = $row[4] ?? null;  //Medida de Paquete
                $catItemMoldCavities = $row[5] ?? null;  //Cavidades Molde
                $catItemStatus = $row[6] ?? null;  //Status
                $catColorsItems = $row[7] ?? null;  //Color
                $catItemProductFamily = $row[8] ?? null;  //Familia de productos
                $catItemSize = $row[9] ?? null;  //Talla | Tamaño
                $catItemMoldProperties = $row[10] ?? null;  //Propiedades de molde

                $internal_id = trim(($internal_id));
                $item = Item:: where('internal_id', $internal_id)
                    ->whereNotNull('internal_id')
                    ->first();

                if ($item !== null && !empty($internal_id)) {

                    $catItemUnitBusiness = $this->findByNameOnTables(CatItemUnitBusiness::query(), $catItemUnitBusiness)->get();
                    $catItemUnitsPerPackage = $this->findByNameOnTables(CatItemUnitsPerPackage::query(), $catItemUnitsPerPackage)->get();
                    $catItemPackageMeasurements = $this->findByNameOnTables(CatItemPackageMeasurement::query(), $catItemPackageMeasurements)->get();
                    $catItemMoldCavities = $this->findByNameOnTables(CatItemMoldCavity::query(), $catItemMoldCavities)->get();
                    $catItemStatus = $this->findByNameOnTables(CatItemStatus::query(), $catItemStatus)->get();
                    $catColorsItems = $this->findByNameOnTables(CatColorsItem::query(), $catColorsItems)->get();
                    $catItemProductFamily = $this->findByNameOnTables(CatItemProductFamily::query(), $catItemProductFamily)->get();
                    $catItemSize = $this->findByNameOnTables(CatItemSize::query(), $catItemSize)->get();
                    $catItemMoldProperties = $this->findByNameOnTables(CatItemMoldProperty::query(), $catItemMoldProperties)->get();


                    /**
                     * @var \Illuminate\Database\Eloquent\Collection $catItemUnitBusiness
                     * @var \Illuminate\Database\Eloquent\Collection $catItemUnitsPerPackage
                     * @var \Illuminate\Database\Eloquent\Collection $catItemPackageMeasurements
                     * @var \Illuminate\Database\Eloquent\Collection $catItemMoldCavities
                     * @var \Illuminate\Database\Eloquent\Collection $catItemStatus
                     * @var \Illuminate\Database\Eloquent\Collection $catColorsItems
                     * @var \Illuminate\Database\Eloquent\Collection $catItemProductFamily
                     * @var \Illuminate\Database\Eloquent\Collection $catItemSize
                     * @var \Illuminate\Database\Eloquent\Collection $catItemMoldProperties
                     */
                    if ($catItemUnitBusiness->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemUnitBusiness::class, 'cat_item_unit_business_id', $catItemUnitBusiness->pluck('id'));
                    }
                    if ($catItemUnitsPerPackage->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemUnitsPerPackage::class, 'cat_item_units_per_package_id', $catItemUnitsPerPackage->pluck('id'));
                    }
                    if (empty($catItemPackageMeasurements)) {
                        $item->setExtraDataByCatalogCategory(ItemPackageMeasurement::class, 'cat_item_package_measurements_id', $catItemPackageMeasurements->pluck('id'));
                    }
                    if ($catItemMoldCavities->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemMoldCavity::class, 'cat_item_mold_cavities_id', $catItemMoldCavities->pluck('id'));
                    }
                    if ($catItemStatus->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemStatus::class, 'cat_item_status_id', $catItemStatus->pluck('id'));
                    }
                    if ($catColorsItems->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemColor::class, 'cat_colors_item_id', $catColorsItems->pluck('id'));
                    }
                    if ($catItemProductFamily->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemProductFamily::class, 'cat_item_product_family_id', $catItemProductFamily);
                    }
                    if ($catItemSize->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemSize::class, 'cat_item_size_id', $catItemSize->pluck('id'));
                    }
                    if ($catItemMoldProperties->count() > 0) {
                        $item->setExtraDataByCatalogCategory(ItemMoldProperty::class, 'cat_item_mold_properties_id', $catItemMoldProperties->pluck('id'));
                    }


                    $registered += 1;

                }

            }

            $this->data = compact('total', 'registered');

        }

        /**
         * Busca el nombre de los elementos en las tablas definidas
         *
         * @param Builder     $query
         * @param string|null $string
         *
         * @return Builder
         */
        protected function findByNameOnTables(Builder $query, ?string $string = ''): Builder
        {
            $string = $string ?? '';
            $array = explode('|', $string);
            if (empty($array)) {
                return $query;
            }
            $query->where(function (Builder $q) use ($array) {
                foreach ($array as $item) {
                    $q->orWhere('name', trim($item));
                }
            });
            return $query;

        }

        public function getData()
        {
            return $this->data;
        }
    }
