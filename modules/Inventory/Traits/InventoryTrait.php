<?php
namespace Modules\Inventory\Traits;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNoteItem;
use Exception;
use Modules\Inventory\Models\Devolution;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Models\InventoryConfiguration;
use Modules\Inventory\Models\InventoryTransaction;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\ItemLot;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Order\Models\OrderNote;
use App\Models\Tenant\ItemSupply;

/**
 * Se debe tener en cuenta este trait para llevar el control de Kardex
 */
trait InventoryTrait
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function optionsEstablishment()
    {
        $records = Establishment::all();
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function optionsItem()
    {
        $records = Item::where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']])->whereNotIsSet()->get();
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    public function optionsItemProduction()
    {
        $records = Item::where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ'], ['is_for_production', 1]])->whereNotIsSet()->get();
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    public function optionsItemSupplies()
    {
        $ids = ItemSupply::select('individual_item_id')->distinct()->pluck('individual_item_id');
        $records = Item::find($ids);
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function optionsItemWareHouse()
    {
        $establishment_id = auth()->user()->establishment_id;
        $current_warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        $records = Item::whereWarehouse()->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']])->whereNotIsSet()->get();
        return collect($records)->transform(function ($row) use ($current_warehouse) {
            return [
                'id' => $row->id,
                'description' => $row->description,
                'lots_enabled' => (bool)$row->lots_enabled,
                'lots' => $row->item_lots->where('has_sale', false)->where('warehouse_id', $current_warehouse->id)->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'series' => $row->series,
                        'date' => $row->date,
                        'item_id' => $row->item_id,
                        'warehouse_id' => $row->warehouse_id,
                        'has_sale' => (bool)$row->has_sale,
                        'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code : null) : null
                    ];
                }),
            ];
        });
    }

    /**
     * @param $warehouse_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function optionsItemWareHousexId($warehouse_id)
    {
        //$establishment_id = auth()->user()->establishment_id;
        //$current_warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

        $records = Item::query()
            ->with('item_lots', 'warehouses')
            ->whereHas('warehouses', function ($query) use ($warehouse_id) {
                $query->where('warehouse_id', $warehouse_id);
            })
            ->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']])
            ->whereNotIsSet()
            ->get();
        return collect($records)->transform(function ($row) use ($warehouse_id) {
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
                \App\Models\Tenant\Warehouse::find($warehouse_id),
                false,
                true

            );
            return array_merge($data, $old);
        });
    }

    /**
     * @param null $search
     * @param null $take
     *
     * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function optionsItemFull($search = null, $take = null)
    {
        $query = Item::query()
            ->with('item_lots', 'item_lots.item_loteable', 'lots_group')
            ->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']])
            ->whereNotIsSet();
        if ($search) {
            $query->where('description', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('internal_id', 'like', "%{$search}%");
        }
        if ($take) {
            $query->take($take);
        }
        return $query->get()->transform(function ($row) {
            $description = $row->description;
            if($row->internal_id) {
                $description .= " | {$row->internal_id}";
            }
            if($row->barcode) {
                $description .= " | {$row->barcode}";
            }
            return [
                'id' => $row->id,
                'description' => $description,
                'lots_enabled' => (bool)$row->lots_enabled,
                'series_enabled' => (bool)$row->series_enabled,
                'lots' => $row->item_lots->where('has_sale', false)->transform(function ($row1) {
                    return [
                        'id' => $row1->id,
                        'series' => $row1->series,
                        'date' => $row1->date,
                        'item_id' => $row1->item_id,
                        'warehouse_id' => $row1->warehouse_id,
                        'has_sale' => (bool)$row1->has_sale,
                        'lot_code' => ($row1->item_loteable_type) ? (isset($row1->item_loteable->lot_code) ? $row1->item_loteable->lot_code : null) : null
                    ];
                }),
                'lots_group' => collect($row->lots_group)->transform(function ($row2) {
                    return [
                        'id' => $row2->id,
                        'code' => $row2->code,
                        'quantity' => $row2->quantity,
                        'date_of_due' => $row2->date_of_due,
                        'checked' => false
                    ];
                })
            ];
        });
    }

    public function optionsItemFullProduction($search = null, $take = null)
    {
        $query = Item::query()
            ->with('item_lots', 'item_lots.item_loteable', 'lots_group','supplies')
            ->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ'], ['is_for_production', 1]])
            ->whereNotIsSet();
        if ($search) {
            $query->where('description', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('internal_id', 'like', "%{$search}%");
        }
        if ($take) {
            $query->take($take);
        }
        return $query->get()->transform(function (Item $row) {
            return $row->getCollectionData();
            $description = $row->description;
            if($row->internal_id) {
                $description .= " | {$row->internal_id}";
            }
            if($row->barcode) {
                $description .= " | {$row->barcode}";
            }
            return [

                'id' => $row->id,
                'description' => $description,
                'lots_enabled' => (bool)$row->lots_enabled,
                'series_enabled' => (bool)$row->series_enabled,
            ];
        });
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|InventoryTransaction|InventoryTransaction[]|null
     */
    public function findInventoryTransaction($id)
    {
        return InventoryTransaction::findOrFail($id);
    }

    /**
     * @param $type
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|InventoryTransaction[]
     */
    public function optionsInventoryTransaction($type)
    {
        $records = InventoryTransaction::where('type', $type)->get();
        return $records;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function optionsWarehouse()
    {
        $records = Warehouse::all();
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    /**
     * @param $item_id
     *
     * @return Item|Item[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function findItem($item_id)
    {
        return Item::find($item_id);
    }

    /**
     * @param null $establishment_id
     *
     * @return \Illuminate\Database\Eloquent\Model|Warehouse
     */
    public function findWarehouse($establishment_id = null)
    {
        if ($establishment_id) {
            $establishment = Establishment::find($establishment_id);
        } else {
            $establishment = auth()->user()->establishment;
        }
        return Warehouse::firstOrCreate([
            'establishment_id' => $establishment->id
        ], [
            'description' => 'Almacén ' . $establishment->description
        ]);
    }

    /**
     * @param $item_id
     * @param $quantity
     * @param $warehouse_id
     *
     * @return \Illuminate\Database\Eloquent\Model|Inventory
     */
    private function createInitialInventory($item_id, $quantity, $warehouse_id)
    {
        return Inventory::create([
            'type' => 1,
            'description' => 'Stock inicial',
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
        ]);
    }
    /**
     * Crea la relacion en inventory_kardex
     *
     * @param Dispatch|Document|Devolution|Item|Inventory|Purchase|SaleNote|\App\Models\Tenant\Warehouse|Warehouse|  OrderNote|mixed $model
     * @param int                                                                                                                    $item_id
     * @param float                                                                                                                  $quantity
     * @param int                                                                                                                    $warehouse_id
     */
    private function createInventoryKardex($model, $item_id, $quantity, $warehouse_id)
    {
        $model->inventory_kardex()->create([
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity,
        ]);
    }
    /**
     * Actualiza el stock
     *
     * @param int $item_id
     * @param float $quantity
     * @param int $warehouse_id
     *
     * @throws Exception
     */
    private function updateStock($item_id, $quantity, $warehouse_id)
    {
        $inventory_configuration = InventoryConfiguration::firstOrFail();
        $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $item_id, 'warehouse_id' => $warehouse_id]);
        $item_warehouse->stock = $item_warehouse->stock + $quantity;
        // dd($item_warehouse->item->unit_type_id);
        if ($quantity < 0 && $item_warehouse->item->unit_type_id !== 'ZZ') {
            if (($inventory_configuration->stock_control) && ($item_warehouse->stock < 0)) {
                // return [
                //     'success' => false,
                //     'message' => 'El producto {$item_warehouse->item->description} no tiene suficiente stock!'
                // ];
                // dd('hasta aqui');
                // return response()->json(['success' => false, 'message' => El producto {$item_warehouse->item->description} no tiene suficiente stock!]);
                throw new Exception("El producto {$item_warehouse->item->description} no tiene suficiente stock!");
            }
        }
        $item_warehouse->save();
    }
    /**
     * Verifica el inventario
     *
     * @param int $item_id
     * @param int $warehouse_id
     *
     * @return bool
     */
    public function checkInventory($item_id, $warehouse_id)
    {
        $inventory = Inventory::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->first();
        return ($inventory) ? true : false;
    }

    /**
     *
     */
    public function initializeInventory()
    {
//        $establishments = Establishment::all();
//        foreach ($establishments as $establishment)
//        {
//            Warehouse::firstOrCreate(['establishment_id' => $establishment->id],
//                                     ['description' => $establishment->description]);
//        }
        $warehouse = $this->findWarehouse();
        $items = Item::all();
        foreach ($items as $item) {
            if (!$this->checkInventory($item->id, $warehouse->id)) {
                $inventory = $this->createInitialInventory($item->id, $item->stock, $warehouse->id);
//                $this->createInventoryKardex($inventory, $item->id, $item->stock, $warehouse->id);
//                $this->updateStock($item->id, $item->stock, $warehouse->id);
            }
        }
    }
    /**
     * Busca por id de almacen
     *
     * @param int $warehouse_id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Warehouse|Warehouse[]|null
     */
    public function findWarehouseById($warehouse_id)
    {
        return Warehouse::findOrFail($warehouse_id);
    }
    ////kardex sale note
    /**
     * Busca Nota de venta por id
     *
     * @param int $sale_note_item_id
     *
     * @return SaleNoteItem|SaleNoteItem[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function findSaleNoteItem($sale_note_item_id)
    {
        return SaleNoteItem::find($sale_note_item_id);
    }
    /**
     * Crea la relacion en inventory_kardex con sale_note
     *
     * @param SaleNote $model
     * @param int                                                                                                                    $item_id
     * @param float                                                                                                                  $quantity
     * @param int                                                                                                                    $warehouse_id
     * @param int                                                                                                                    $sale_note_item_id
     */
    private function createInventoryKardexSaleNote($model, $item_id, $quantity, $warehouse_id, $sale_note_item_id)
    {
        $sale_note_kardex = $model->inventory_kardex()->create([
            'date_of_issue' => date('Y-m-d'),
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity,
        ]);
        $sale_note_item = $this->findSaleNoteItem($sale_note_item_id);
        $sale_note_item->inventory_kardex_id = $sale_note_kardex->id;
        $sale_note_item->update();
    }
    /**
     * Borra el kardex basado en el id de  $inventory_kardex_id
     *
     * @param Dispatch|Document|Devolution|Item|Inventory|Purchase|SaleNote|\App\Models\Tenant\Warehouse|Warehouse|  OrderNote|mixed $model
     * @param                                                                                                                        $inventory_kardex_id
     */
    private function deleteInventoryKardex($model, $inventory_kardex_id)
    {
        $model->inventory_kardex()->where('id', $inventory_kardex_id)->delete();
    }
    ////kardex sale note
    /**
     * Elimina todos los inventory_kardex por modelo
     * @param Dispatch|Document|Devolution|Item|Inventory|Purchase|SaleNote|\App\Models\Tenant\Warehouse|Warehouse|  OrderNote|mixed $model
     */
    private function deleteAllInventoryKardexByModel($model)
    {
        $model->inventory_kardex()->delete();
    }
    /**
     * Actualiza los lotes por el document Item
     *
     * @param DocumentItem $document_item
     */
    private function updateDataLots($document_item)
    {
        // dd($document_item);
        if (isset($document_item->item->IdLoteSelected)) {
            if ($document_item->item->IdLoteSelected != null) {

                if(is_array($document_item->item->IdLoteSelected)) 
                { 

                    // presentacion - factor de lista de precios
                    $quantity_unit = isset($document_item->item->presentation->quantity_unit) ? $document_item->item->presentation->quantity_unit : 1;
                    $lotesSelecteds = $document_item->item->IdLoteSelected;

                    foreach ($lotesSelecteds as $item) 
                    {
                        $lot = ItemLotsGroup::query()->find($item->id);
                        $lot->quantity = $lot->quantity + ($quantity_unit * $item->compromise_quantity);
                        $lot->save();
                    }
                    
                }else {
                    $lot = ItemLotsGroup::find($document_item->item->IdLoteSelected);
                    $lot->quantity = $lot->quantity + $document_item->quantity;
                    $lot->save();
                }

              
            }
        }
        if (isset($document_item->item->lots)) {
            foreach ($document_item->item->lots as $it) {
                if ($it->has_sale == true) {
                    $r = ItemLot::find($it->id);
                    $r->has_sale = false;
                    $r->save();
                }
            }
        }
    }
    /**
     * Valida los lotes de un item para borrarlos individualmente
     *
     * @param SaleNoteItem $item
     */
    private function deleteItemLots($item)
    {
        $i_lots_group = isset($item->item->lots_group) ? $item->item->lots_group : [];
        $lot_group_selecteds_filter = collect($i_lots_group)->where('compromise_quantity', '>', 0);
        $lot_group_selecteds =  $lot_group_selecteds_filter->all();

        if (count($lot_group_selecteds) > 0) {
            foreach ($lot_group_selecteds as $lt) {
                $lot = ItemLotsGroup::find($lt->id);
                $lot->quantity = $lot->quantity + $lt->compromise_quantity;
                $lot->save();
            }
        }
        if (isset($item->item->lots)) {
            foreach ($item->item->lots as $it) {
                if ($it->has_sale == true) {
                    $ilt = ItemLot::find($it->id);
                    $ilt->has_sale = false;
                    $ilt->save();
                }
            }
        }
    }
    /**
     * Valida los documentos cuando es un set
     *
     * @param DocumentItem $detail
     *
     * @throws Exception
     */
    public function voidedDocumentItemSet($detail)
    {
        $document_item = $detail;
        $item = Item::findOrFail($document_item->item_id);
        foreach ($item->sets as $it) {
            $ind_item = $it->individual_item;
            $item_set_quantity = ($it->quantity) ? $it->quantity : 1;
            $presentationQuantity = 1;
            $document = $document_item->document;
            $factor = 1;
            $warehouse = $this->findWarehouse();
            $this->createInventoryKardex($document_item->document, $ind_item->id, ($factor * ($document_item->quantity * $presentationQuantity * $item_set_quantity)), $warehouse->id);
            if (!$document_item->document->sale_note_id && !$document_item->document->order_note_id && !$document_item->document->sale_notes_relateds) $this->updateStock($ind_item->id, ($factor * ($document_item->quantity * $presentationQuantity * $item_set_quantity)), $warehouse->id);
        }
    }
    /**
     * Verifica si el producto ha tenido series en venta
     *
     * @param PurchaseItem $purchase_item
     *
     * @throws Exception
     */
    public function verifyHasSaleLots($purchase_item)
    {
        $validated = true;
        $items = $purchase_item->lots;
        foreach ($items as $element) {
            //$lot_has_sale = collect($element->lots)->firstWhere('has_sale', 1);
            if ($element->has_sale == 1) {
                $validated = false;
                break;
            }
        }
        if ($validated == false) {
            throw new Exception("El producto {$purchase_item->item->description} contiene series vendidas!");
        }
    }
    /**
     * Verifica si el producto ha tenido lotes en venta
     *
     * @param PurchaseItem $purchase_item
     *
     * @throws Exception
     */
    public function verifyHasSaleLotsGroup($purchase_item)
    {
        $lot_enabled = false;
        if(is_array($purchase_item->item)){
            if(in_array('lots_enabled',$purchase_item->item)){
                $lot_enabled = true;
            }
        }elseif(is_object($purchase_item->item)){
            if(property_exists($purchase_item->item,'lots_enabled')){
                $lot_enabled = true;
            }
        }
        if($lot_enabled) {
        // if(array_key_exists('lots_enabled', $purchase_item->item)) {
            if ($purchase_item->item->lots_enabled && $purchase_item->lot_code) {
                $lot_group = ItemLotsGroup::where('code', $purchase_item->lot_code)->first();
                if (!$lot_group) {
                    throw new Exception("El lote {$purchase_item->lot_code} no existe!");
                }

                // factor de lista de precios
                $presentation_quantity = (isset($purchase_item->item->presentation->quantity_unit)) ? $purchase_item->item->presentation->quantity_unit : 1;

                if ((int)$lot_group->quantity != (int) ($purchase_item->quantity * $presentation_quantity)) {
                    throw new Exception("Los productos del lote {$purchase_item->lot_code} han sido vendidos!");
                }
            }
        }
    }
    /**
     * Borra las series y grupos en la compra para un item
     *
     * @param PurchaseItem $purchase_item
     *
     * @throws Exception
     */
    public static function deleteItemSeriesAndGroup($purchase_item)
    {
        $series = $purchase_item->lots;
        foreach ($series as $row) {
            $it = ItemLot::findOrFail($row->id);
            $it->delete();
        }
        $lot_enabled = false;
        if(is_array($purchase_item->item)){
            if(in_array('lots_enabled',$purchase_item->item)){
                $lot_enabled = true;
            }
        }elseif(is_object($purchase_item->item)){
            if(property_exists($purchase_item->item,'lots_enabled')){
                $lot_enabled = true;
            }
        }
        if($lot_enabled) {
        //if(array_key_exists('lots_enabled', $purchase_item->item)) {
            if ($purchase_item->item->lots_enabled && $purchase_item->lot_code) {
                $lot_group = ItemLotsGroup::where('code', $purchase_item->lot_code)->firstOrFail();
                if (!$lot_group) {
                    throw new Exception("El lote {$purchase_item->lot_code} no existe!");
                }
                $lot_group->delete();
            }
        }
    }
    /**
     * Actualiza el stock para compras
     *
     * @param int $item_id
     * @param float $quantity
     * @param int $warehouse_id
     */
    private function updateStockPurchase($item_id, $quantity, $warehouse_id)
    {
        $inventory_configuration = InventoryConfiguration::firstOrFail();
        $item_warehouse = ItemWarehouse::firstOrNew(['item_id' => $item_id, 'warehouse_id' => $warehouse_id]);
        $item_warehouse->stock = $item_warehouse->stock + $quantity;
        $item_warehouse->save();
    }

    /**
     * Al borrar item, se descuenta el stock
     * @param DocumentItem $document_item
     * @param int          $factor
     *
     * @throws Exception
     */
    public function processIndividualDocumentItem($document_item, $factor = 1)
    {
        $presentationQuantity = (!empty($document_item->item->presentation)) ? $document_item->item->presentation->quantity_unit : 1;
        $document = $document_item->document;
        $warehouse = ($document_item->warehouse_id) ? $this->findWarehouse($this->findWarehouseById($document_item->warehouse_id)->establishment_id) : $this->findWarehouse();

        $this->createInventoryKardex($document_item->document, $document_item->item_id, ($factor * ($document_item->quantity * $presentationQuantity)), $warehouse->id);

        if (!$document_item->document->sale_note_id && !$document_item->document->order_note_id && !$document_item->document->dispatch_id && !$document_item->document->sale_notes_relateds) 
        {
            $this->updateStock($document_item->item_id, ($factor * ($document_item->quantity * $presentationQuantity)), $warehouse->id);
        } else
        {
            if ($document_item->document->dispatch) {
                if (!$document_item->document->dispatch->transfer_reason_type->discount_stock) {
                    $this->updateStock($document_item->item_id, ($factor * ($document_item->quantity * $presentationQuantity)), $warehouse->id);
                }
            }
        }

    }


    /**
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|InventoryTransaction[]
     */
    public function allInventoryTransaction()
    {
        return InventoryTransaction::get();
    }
    
    /**
     * 
     * Validar si el lote cuenta con stock disponible, controla descuento de lotes individuales y por presentacion
     * 
     * Usado en:
     * InventoryKardexServiceProvider - método sale (venta cpe)
     * SaleNoteController - método store (registro nota venta)
     * 
     * @param $lot
     * @param $document_item
     * @return void
     */
    public function validateStockLotGroup($lot, $document_item)
    {
        if($lot->quantity < 0)
        {
            throw new Exception("El lote '{$lot->code}' del producto {$document_item->item->description} no tiene suficiente stock!");
        }
    }

}
