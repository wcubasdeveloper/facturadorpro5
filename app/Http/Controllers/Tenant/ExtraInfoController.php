<?php

    namespace App\Http\Controllers\Tenant;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Catalogs\CatColorsItem;
    use App\Models\Tenant\Catalogs\CatItemMoldCavity;
    use App\Models\Tenant\Catalogs\CatItemMoldProperty;
    use App\Models\Tenant\Catalogs\CatItemPackageMeasurement;
    use App\Models\Tenant\Catalogs\CatItemProductFamily;
    use App\Models\Tenant\Catalogs\CatItemStatus;
    use App\Models\Tenant\Catalogs\CatItemUnitBusiness;
    use App\Models\Tenant\Catalogs\CatItemUnitsPerPackage;
    use App\Models\Tenant\CatItemSize;
    use App\Models\Tenant\Configuration;

    class ExtraInfoController extends Controller

    {

        /**
         * Devuelve la coleccion de datos extra para los items
         *
         * @return array
         */
        public function getExtraDataForItems()
        {
            $configuration = Configuration::first();

            /** Informacion adicional */
            $colors = collect([]);
            $CatItemSize = $colors;
            $CatItemStatus = $colors;
            $CatItemUnitBusiness = $colors;
            $CatItemMoldCavity = $colors;
            $CatItemPackageMeasurement = $colors;
            $CatItemUnitsPerPackage = $colors;
            $CatItemMoldProperty = $colors;
            $CatItemProductFamily = $colors;
            if ($configuration->isShowExtraInfoToItem()) {

                $colors = CatColorsItem::all();
                $CatItemSize = CatItemSize::all();
                $CatItemStatus = CatItemStatus::all();
                $CatItemUnitBusiness = CatItemUnitBusiness::all();
                $CatItemMoldCavity = CatItemMoldCavity::all();
                $CatItemPackageMeasurement = CatItemPackageMeasurement::all();
                $CatItemUnitsPerPackage = CatItemUnitsPerPackage::all();
                $CatItemMoldProperty = CatItemMoldProperty::all();
                $CatItemProductFamily = CatItemProductFamily::all();
            }
            $configuration = $configuration->getCollectionData();
            return compact(
                'configuration',
                'colors',
                'CatItemSize',
                'CatItemMoldCavity',
                'CatItemMoldProperty',
                'CatItemUnitBusiness',
                'CatItemStatus',
                'CatItemPackageMeasurement',
                'CatItemProductFamily',
                'CatItemUnitsPerPackage'
            );
        }

    }
