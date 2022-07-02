<?php

    namespace App\Traits;

    use App\Models\Tenant\DispatchItem;
    use App\Models\Tenant\DocumentItem;
    use App\Models\Tenant\ItemMovement;
    use App\Models\Tenant\ItemMovementRelExtra;
    use App\Models\Tenant\PurchaseItem;
    use App\Models\Tenant\QuotationItem;
    use App\Models\Tenant\SaleNoteItem;
    use App\Models\Tenant\TechnicalServiceItem;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Query\Builder;
    use Log;
    use Modules\Expense\Models\ExpenseItem;
    use Modules\Inventory\Models\DevolutionItem;
    use Modules\Order\Models\OrderFormItem;
    use Modules\Order\Models\OrderNoteItem;
    use Modules\Purchase\Models\FixedAssetItem;
    use Modules\Purchase\Models\FixedAssetPurchaseItem;
    use Modules\Purchase\Models\PurchaseOrderItem;
    use Modules\Purchase\Models\PurchaseQuotationItem;
    use Modules\Sale\Models\ContractItem;
    use Modules\Sale\Models\SaleOpportunityItem;


    /**
     * Se le hace seguimiento  a los modelos para que, al momento de observarse un cambio, puedan guardarse
     * satisfactoriamente.
     */

    trait AttributePerItems
    {
        public static function bootAttributePerItems()
        {

            static::deleted(function ($model) {
                self::adsjustItemMovementTable($model, 'deleted');
            });
            static::updated(function ($model) {
               // Log::debug(__FILE__."::" .__LINE__ . " esto en updated adsjustItemMovementTable " . $model->id . " / " . get_class($model));
                 self::adsjustItemMovementTable($model, 'updated');
            });
            static::created(function ($model) {
                //Log::debug(__FILE__."::" .__LINE__ . " esto en created adsjustItemMovementTable " . $model->id . " / " . get_class($model));
                self::adsjustItemMovementTable($model, 'created');
            });
            static::saved(function ($model) {
                // self::adsjustItemMovementTable($model, 'saved');
            });

        }

        /**
         * @param mixed  $model
         * @param string $evento
         *
         * @return ItemMovement|\Illuminate\Database\Eloquent\Builder|Model|Builder|mixed|object|null
         */
        public static function adsjustItemMovementTable($model, $evento = '')
        {
            if (empty($model)) return null;
            $modelClass = get_class($model);
            if (!in_array($modelClass, [
                DevolutionItem::class,
                ContractItem::class,
                DispatchItem::class,
                DocumentItem::class,
                ExpenseItem::class,
                FixedAssetPurchaseItem::class,
                OrderFormItem::class,
                OrderNoteItem::class,
                PurchaseItem::class,
                PurchaseOrderItem::class,
                PurchaseQuotationItem::class,
                QuotationItem::class,
                SaleNoteItem::class,
                SaleOpportunityItem::class,
                TechnicalServiceItem::class,

            ])) {
                return null;
            }


            $arrayModel = $model->toArray();
            if (
                !isset($arrayModel['quantity']) ||
                !isset($arrayModel['item_id'])
            ) {
                return $model;
            }

            $item = $model->item_id;
            $qty = $model->quantity;

            $datetime = null;
            if (isset($arrayModel['date_of_issue'])) {
                $datetime = $model->date_of_issue;
                if (isset($arrayModel['time_of_issue'])) {
                    $datetime .= " " . $model->time_of_issue;
                } else {
                    $datetime .= " 00:00:00";
                }
            }

            $toSearch = [
                'item_id' => $item,
                'date_of_movement' => $datetime,
                'contract_item_id' => 0,
                'devolution_item_id' => 0,
                'dispatch_item_id' => 0,
                'document_item_id' => 0,
                'expense_item_id' => 0,
                'fixed_asset_item_id' => 0,
                'fixed_asset_purchase_item_id' => 0,
                'order_form_item_id' => 0,
                'order_note_item_id' => 0,
                'purchase_item_id' => 0,
                'purchase_order_item_id' => 0,
                'purchase_quotation_item_id' => 0,
                'quotation_item_id' => 0,
                'sale_note_item_id' => 0,
                'sale_opportunity_item_id' => 0,
                'technical_service_item_id' => 0,
            ];
            $id = $model->id;
            $modelClass = get_class($model);
            self::setArrayToSearch($toSearch, $modelClass, $id);


            // Ajuste para cantidades. Si es un ingreso deberia ser positivo, si es un egreso, quedar en negativo
            $Countable = false;
            self::adjustQuantityOfItem($modelClass, $qty, $Countable);


            $movement = ItemMovement::where($toSearch)->first();
            if ($movement == null) {
                $movement = new ItemMovement($toSearch);
            }
            $establisment_id = 0;
            $parentModel = $movement->getParentModel();
            if (empty($parentModel)) {
                // Si no existe el padre, se establece en 0 el elemento y previene ser contado
                $movement
                    ->setQuantity(0)
                    ->setCountable(false)
                    ->push();
                return null;
            } else {
                $arrayModelParent = $parentModel->toArray();
                if (isset($arrayModelParent['establishment_id'])) {
                    $establisment_id = $arrayModelParent['establishment_id'];
                }
                if ($datetime == null) {
                    if ($parentModel->timestamps) {
                        $movement->setDateOfMovement($parentModel->created_at);
                    }
                }
            }

            $movement
                ->setCountable($Countable)
                ->setQuantity($qty)
                ->setEstablishmentId($establisment_id)
                ->push();

            if ($evento == 'deleted') {
                $movement->delete();
                return $movement;
            }

            self::processExtraData($arrayModel, $movement);
            return $movement;

        }

        /**
         * Ajusta la busqueda determinando la tabla correspondiente
         *
         * @param array  $toSearch
         * @param string $modelClass
         * @param int    $id
         */
        public static function setArrayToSearch(&$toSearch, $modelClass, $id)
        {
            if (DevolutionItem::class == $modelClass) {
                $toSearch['devolution_item_id'] = $id;
            } elseif (ContractItem::class == $modelClass) {
                $toSearch['contract_item_id'] = $id;
            } elseif (DispatchItem::class == $modelClass) {
                $toSearch['dispatch_item_id'] = $id;
            } elseif (DocumentItem::class == $modelClass) {
                $toSearch['document_item_id'] = $id;
            } elseif (ExpenseItem::class == $modelClass) {
                $toSearch['expense_item_id'] = $id;
            } elseif (FixedAssetPurchaseItem::class == $modelClass) {
                $toSearch['fixed_asset_purchase_item_id'] = $id;
            } elseif (OrderFormItem::class == $modelClass) {
                $toSearch['order_form_item_id'] = $id;
            } elseif (OrderNoteItem::class == $modelClass) {
                $toSearch['order_note_item_id'] = $id;
            } elseif (PurchaseItem::class == $modelClass) {
                $toSearch['purchase_item_id'] = $id;
            } elseif (PurchaseOrderItem::class == $modelClass) {
                $toSearch['purchase_order_item_id'] = $id;
            } elseif (PurchaseQuotationItem::class == $modelClass) {
                $toSearch['purchase_quotation_item_id'] = $id;
            } elseif (QuotationItem::class == $modelClass) {
                $toSearch['quotation_item_id'] = $id;
            } elseif (SaleNoteItem::class == $modelClass) {
                $toSearch['sale_note_item_id'] = $id;
            } elseif (SaleOpportunityItem::class == $modelClass) {
                $toSearch['sale_opportunity_item_id'] = $id;
            } elseif (TechnicalServiceItem::class == $modelClass) {
                $toSearch['technical_service_item_id'] = $id;
            } elseif (FixedAssetItem::class == $modelClass) {
                $toSearch['fixed_asset_item_id'] = $id;
            }
        }

        /**
         * Establece la cantidad basado en el tipo de modelo, tambien define si se tomará en cuenta para contar totales
         *
         * @param string    $modelClass
         * @param int|float $qty
         * @param bool      $Countable
         */
        public static function adjustQuantityOfItem($modelClass, &$qty, &$Countable)
        {
            /*
              ContractItem::class  == $modelClass ||
              DispatchItem::class  == $modelClass ||
              ExpenseItem::class  == $modelClass ||
              FixedAssetPurchaseItem::class  == $modelClass ||
              OrderFormItem::class  == $modelClass ||
              OrderNoteItem::class  == $modelClass ||
              PurchaseOrderItem::class  == $modelClass ||
              PurchaseQuotationItem::class  == $modelClass ||
              QuotationItem::class  == $modelClass ||
              SaleOpportunityItem::class  == $modelClass ||
              FixedAssetItem::class  == $modelClass ||
              */

            if (
                DocumentItem::class == $modelClass ||
                TechnicalServiceItem::class == $modelClass ||
                SaleNoteItem::class == $modelClass
            ) {
                // Un egreso de item. la cantidad será restada
                $Countable = true;
                $qty *= (-1);
                // @todo Se necesitará evaluar nota de credito y debito para que afecte correctamente.
            } elseif (
                PurchaseItem::class == $modelClass ||
                DevolutionItem::class == $modelClass

            ) {
                // Un ingreso, se registra como positivo
                $Countable = true;

            }

        }


        /**
         * Lee el campo de datos extra del item, para poder almacenarlo correctamente en tabla.
         * Solo se almacenará datos de items que tengan campos extra
         *
         * @param array             $arrayModel
         * @param ItemMovement|null $movement
         *
         * @return void|null
         */
        public static function processExtraData($arrayModel = [], ItemMovement $movement = null)
        {
            if (isset($arrayModel['item'])) {
                $item = $arrayModel['item'];
                // Solo aplica cuando extra existe
                if (!property_exists($item, 'extra')) {
                    return null;
                }
                $extra = $item->extra;
                    self::LogInfo(__LINE__, $extra);


                $colors = null;
                $CatItemUnitsPerPackage = null;
                $CatItemMoldProperty = null;
                $CatItemProductFamily = null;
                $CatItemSize = null;
                $CatItemStatus = null;
                $CatItemUnitBusiness = null;
                $CatItemPackageMeasurement = null;
                $CatItemMoldCavity = null;
                $canSave = false;
                if (property_exists($extra, 'colors')) {
                    $colors = $extra->colors;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemUnitsPerPackage')) {
                    $CatItemUnitsPerPackage = $extra->CatItemUnitsPerPackage;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemMoldProperty')) {
                    $CatItemMoldProperty = $extra->CatItemMoldProperty;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemProductFamily')) {
                    $CatItemProductFamily = $extra->CatItemProductFamily;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemMoldCavity')) {
                    $CatItemMoldCavity = $extra->CatItemMoldCavity;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemPackageMeasurement')) {
                    $CatItemPackageMeasurement = $extra->CatItemPackageMeasurement;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemStatus')) {
                    $CatItemStatus = $extra->CatItemStatus;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemUnitBusiness')) {
                    $CatItemUnitBusiness = $extra->CatItemUnitBusiness;
                    $canSave = true;
                }
                if (property_exists($extra, 'CatItemSize')) {
                    $CatItemSize = $extra->CatItemSize;
                    $canSave = true;
                }

                if ($canSave == true) {


                    $extraRow = $movement->item_movement_rel_extra;
                    if (empty($extraRow)) $extraRow = new ItemMovementRelExtra([
                        'item_id' => $movement->item_id,
                        'item_movement_id' => $movement->id,
                    ]);


                    $extraRow
                        ->setItemStatusId($CatItemStatus)
                        ->setItemColorId($colors)
                        ->setItemUnitsPerPackageId($CatItemUnitsPerPackage)
                        ->setItemMoldPropertiesId($CatItemMoldProperty)
                        ->setItemProductFamilyId($CatItemProductFamily)
                        ->setItemSizeId($CatItemSize)
                        ->setItemUnitBusinessId($CatItemUnitBusiness)
                        ->setItemPackageMeasurementsId($CatItemPackageMeasurement)
                        ->setItemMoldCavitiesId($CatItemMoldCavity)
                        ->push();


                }
                self::LogInfo(__LINE__, "Evaluando item en processExtraData " . __FILE__);
                self::LogInfo(__LINE__, $colors);
                self::LogInfo(__LINE__, $CatItemUnitsPerPackage);
                self::LogInfo(__LINE__, $CatItemMoldProperty);
                self::LogInfo(__LINE__, $CatItemProductFamily);
                self::LogInfo(__LINE__, $CatItemMoldCavity);
                self::LogInfo(__LINE__, $CatItemPackageMeasurement);
                self::LogInfo(__LINE__, $CatItemSize);
                self::LogInfo(__LINE__, $CatItemStatus);
                self::LogInfo(__LINE__, $CatItemUnitBusiness);

            }
        }
        public static function LogInfo($linea, $elemento)
        {

            /*
            if (is_object($elemento)) {
                Log::debug(" \n" . $linea . "\n" . var_export($elemento, true));
            } else {
                Log::debug(" \n" . $linea . "\n$elemento");

            }
            */
        }

    }
