<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Dispatch;
use App\Models\Tenant\Document;
use App\Models\Tenant\Item;
use App\Models\Tenant\ItemWarehousePrice;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\SaleNote;
use Modules\Order\Models\OrderNote;

/**
 * Modules\Inventory\Models\InventoryKardex
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $inventory_kardexable
 * @property-read Item $item
 * @property-read \Modules\Inventory\Models\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryKardex query()
 * @mixin ModelTenant
 */
class InventoryKardex extends ModelTenant
{
    protected $table = 'inventory_kardex';

    protected $fillable = [
        'date_of_issue',
        'item_id',
        'inventory_kardexable_id',
        'inventory_kardexable_type',
        'warehouse_id',
        'quantity',
    ];

    public function inventory_kardexable()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return ItemWarehousePrice|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    public function getItemWarehousePriceModel()
    {
        return ItemWarehousePrice::where(
            [
                'warehouse_id' => $this->warehouse_id,
                'item_id' => $this->item_id,
            ]
        )->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|Warehouse|Warehouse[]|null
     */
    public function getWarehouseModel()
    {
        return Warehouse::find($this->warehouse_id);
    }
    
    /**
     * Obtener notas de venta asociadas a documento
     *
     * @return string
     */
    public function getSaleNoteAsoc($inventory_kardexable)
    {
        $sale_note_asoc = "-";

        if(isset($inventory_kardexable->sale_note_id))
        {
            $sale_note_asoc = optional($inventory_kardexable)->sale_note->number_full;
        }

        if(isset($inventory_kardexable->sale_notes_relateds))
        {
            $data = [];

            foreach ($inventory_kardexable->sale_notes_relateds as $sale_note) 
            {
                if(isset($sale_note->items)){
                    
                    $exist_sale_note = collect($sale_note->items)->where('item_id', $this->item_id)->first();
    
                    if($exist_sale_note) $data [] = $sale_note->number_full;
                }
            }

            // $sale_note_asoc = collect($inventory_kardexable->sale_notes_relateds)->implode('number_full', ', ');
            $sale_note_asoc = count($data) > 0 ? implode(', ', $data) : '-';

        }

        return $sale_note_asoc;
    }

    /**
     * @param $balance
     * @return array
     */
    public function getKardexReportCollection(&$balance)
    {
        $models = [
            Document::class,
            Purchase::class,
            SaleNote::class,
            Inventory::class,
            OrderNote::class,
            Devolution::class,
            Dispatch::class
        ];
        $item = $this->item;
        $warehouseprice = $this->getItemWarehousePriceModel();
        $warehouse = $this->getWarehouseModel();
        $price = '-';
        $warehouseName = '';
        if (!empty($warehouseprice)) {
            $price = $warehouseprice->getPrice();
        }
        if (!empty($warehouse)) {
            $warehouseName = $warehouse->description;
        }
        $data = [
            'id' => $this->id,
            'item_name' => $item->description,
            'date_time' => $this->created_at->format('Y-m-d H:i:s'),
            'date_of_issue' => '-',
            'number' => '-',
            'sale_note_asoc' => '-',
            'order_note_asoc' => '-',
            'doc_asoc' => '-',
            // 'inventory_kardexable_id' => $this->inventory_kardexable_id,
            'inventory_kardexable_type' => $this->inventory_kardexable_type,
            // 'item' => $item->getCollectionData(),
            'item_warehouse_price' => $price,
            'warehouse' => $warehouseName,
        ];
        $inventory_kardexable = $this->inventory_kardexable;
        $qty = $this->quantity;
        $input_set = ($qty > 0) ? $qty : "-";
        $output_set = ($qty < 0) ? $qty : "-";
        $data['input'] = $input_set;
        $data['output'] = $output_set;
        switch ($this->inventory_kardexable_type) {

            case $models[0]: //venta

                $cpe_input = ($qty > 0) ? (isset($inventory_kardexable->sale_note_id) || isset($inventory_kardexable->order_note_id) || isset($inventory_kardexable->sale_notes_relateds) ? "-" : $qty) : "-";
                
                $cpe_output = ($qty < 0) ? (isset($inventory_kardexable->sale_note_id) || isset($inventory_kardexable->order_note_id) || isset($inventory_kardexable->sale_notes_relateds) ? "-" : $qty) : "-";

                $cpe_discounted_stock = false;
                $cpe_doc_asoc = isset($inventory_kardexable->note) ? $inventory_kardexable->note->affected_document->getNumberFullAttribute() : '-';

                if (isset($inventory_kardexable->dispatch)) {
                    if ($inventory_kardexable->dispatch->transfer_reason_type->discount_stock) {
                        $cpe_output = '-';
                        $cpe_discounted_stock = true;
                    }
                    $cpe_doc_asoc = ($cpe_doc_asoc == '-') ? $inventory_kardexable->dispatch->number_full : $cpe_doc_asoc . ' | ' . $inventory_kardexable->dispatch->number_full;
                }

                $doc_balance = (isset($inventory_kardexable->sale_note_id) || isset($inventory_kardexable->order_note_id) || $cpe_discounted_stock || isset($inventory_kardexable->sale_notes_relateds)) ? $balance += 0 : $balance += $qty;

                $data['input'] = $cpe_input;
                $data['output'] = $cpe_output;
                $data['balance'] = $doc_balance;
                $data['number'] = optional($inventory_kardexable)->series . '-' . optional($inventory_kardexable)->number;
                $data['type_transaction'] = ($qty < 0) ? "Venta" : "Anulación Venta";
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                // $data['sale_note_asoc'] = isset($inventory_kardexable->sale_note_id) ? optional($inventory_kardexable)->sale_note->number_full : "-";
                $data['sale_note_asoc'] = $this->getSaleNoteAsoc($inventory_kardexable);
                $data['doc_asoc'] = $cpe_doc_asoc;
                $data['order_note_asoc'] = isset($inventory_kardexable->order_note_id) ? optional($inventory_kardexable)->order_note->number_full : "-";
                break;

            case $models[1]:
                $data['balance'] = $balance += $qty;
                $data['number'] = optional($inventory_kardexable)->series . '-' . optional($inventory_kardexable)->number;
                $data['type_transaction'] = ($qty < 0) ? "Anulación Compra" : "Compra";
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                break;
            case $models[2]: // Nota de venta
                $data['balance'] = $balance += $qty;
                $data['number'] = optional($inventory_kardexable)->number_full;
                $data['type_transaction'] = "Nota de venta";
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                break;
            case $models[3]:
            {
                $transaction = '';
                $input = '';
                $output = '';
                if (!$inventory_kardexable->type) {
                    $transaction = InventoryTransaction::findOrFail($inventory_kardexable->inventory_transaction_id);
                }
                if ($inventory_kardexable->type != null) {
                    $input = ($inventory_kardexable->type == 1) ? $qty : "-";
                } else {
                    $input = ($transaction->type == 'input') ? $qty : "-";
                }
                if ($inventory_kardexable->type != null) {
                    $output = ($inventory_kardexable->type == 2 || $inventory_kardexable->type == 3) ? $qty : "-";
                } else {
                    $output = ($transaction->type == 'output') ? $qty : "-";
                }
                $user = auth()->user();
                $data['balance'] = $balance += $qty;
                $data['type_transaction'] = $inventory_kardexable->description;
                if ($inventory_kardexable->warehouse_destination_id === $user->establishment_id) {
                    $data['input'] = $output;
                    $data['output'] = $input;
                } else {
                    $data['input'] = $input;
                    $data['output'] = $output;
                }
                break;
            }
            case $models[4]:
                $data['balance'] = $balance += $qty;
                $data['number'] = optional($inventory_kardexable)->prefix . '-' . optional($inventory_kardexable)->id;
                $data['type_transaction'] = ($qty < 0) ? "Pedido" : "Anulación Pedido";
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                break;
            case $models[5]: // Devolution
                $data['balance'] = $balance += $qty;
                $data['number'] = optional($inventory_kardexable)->number_full;
                $data['type_transaction'] = "Devolución";
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                break;
            case $models[6]: // Dispatch
                $data['input'] = ($qty > 0) ? (isset($inventory_kardexable->reference_sale_note_id) || isset($inventory_kardexable->reference_order_note_id) || isset($inventory_kardexable->reference_document_id) ? "-" : $qty) : "-";
                $data['output'] = ($qty < 0) ? (isset($inventory_kardexable->reference_sale_note_id) || isset($inventory_kardexable->reference_order_note_id) || isset($inventory_kardexable->reference_document_id) ? "-" : $qty) : "-";
                $data['balance'] = (isset($inventory_kardexable->reference_sale_note_id) || isset($inventory_kardexable->reference_order_note_id) || isset($inventory_kardexable->reference_document_id)) ? $balance += 0 : $balance += $qty;
                $data['number'] = optional($inventory_kardexable)->number_full;
                $data['type_transaction'] = isset($inventory_kardexable->transfer_reason_type->description) ? $inventory_kardexable->transfer_reason_type->description : '';
                $data['date_of_issue'] = isset($inventory_kardexable->date_of_issue) ? $inventory_kardexable->date_of_issue->format('Y-m-d') : '';
                $data['sale_note_asoc'] = isset($inventory_kardexable->reference_sale_note_id) ? optional($inventory_kardexable)->sale_note->number_full : "-";
                $data['order_note_asoc'] = isset($inventory_kardexable->reference_order_note_id) ? optional($inventory_kardexable)->order_note->number_full : "-";
                $data['doc_asoc'] = isset($inventory_kardexable->reference_document_id) ? $inventory_kardexable->reference_document->getNumberFullAttribute() : '-';
                break;
        }
        $decimalRound = 6; // Cantidad de decimales a aproximar
        $data['balance'] =$data['balance'] ? round( $data['balance'] ,$decimalRound):0;
        return $data;
    }
}
