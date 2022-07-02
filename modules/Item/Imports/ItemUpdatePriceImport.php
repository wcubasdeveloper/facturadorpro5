<?php

namespace Modules\Item\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use App\Models\Tenant\ItemUnitType;


class ItemUpdatePriceImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);

            foreach ($rows as $row)
            {

                $internal_id = $row[0] ?? null;
                $sale_unit_price = $row[1];
                $purchase_unit_price = $row[2] ?? null;
                $item = null;

                if($internal_id) $item = Item::whereFilterUpdatePrices($internal_id)->first();

                // dd($item);
                
                if($item) 
                {
                    $item->sale_unit_price = $sale_unit_price;

                    if($purchase_unit_price)
                    {
                        $item->purchase_unit_price = $purchase_unit_price;
                    }

                    $item->update();
                    $registered += 1;
                } 

            }

            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
