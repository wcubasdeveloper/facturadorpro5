<?php

namespace Modules\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Inventory\Models\InventoryTransfer;

class InventoryTransferExport implements  FromView, ShouldAutoSize
{
    use Exportable;


    /**
     * @return \Modules\Inventory\Models\InventoryTransfer
     */
    public function getInventory(): InventoryTransfer
    {
        return $this->inventory;
    }

    /**
     * @param \Modules\Inventory\Models\InventoryTransfer $inventory
     *
     * @return InventoryTransferExport
     */
    public function setInventory(InventoryTransfer $inventory): InventoryTransferExport
    {
        $this->inventory = $inventory;
        $this->data = $inventory->getPdfData();

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return InventoryTransferExport
     */
    public function setData(array $data): InventoryTransferExport
    {
        $this->data = $data;
        return $this;
    }

    public function view(): View {

        return view('inventory::transfers.export.excel', [
            'data' => $this->getData()
        ]);

    }

}
