<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Item;
use Illuminate\Http\Request;


class ItemController extends Controller
{

        
    /**
     * 
     * Búsqueda avanzada de items para reporte kardex
     *
     * @param  Request $request
     * @return array
     */
    public function advancedItemsSearch(Request $request)
    {

        $items = Item::whereFilterReportKardex()->latest();

        if($request->has('search_value'))
        {
            $items->whereAdvancedRecordsSearch('description', $request->search_value)
                    ->orWhere('internal_id', $request->search_value);
        }
        else
        {
            $items->take(10);
        }

        return [
            'items' => $items->get()->transform(function ($row) {
                return $row->getRowResourceAdvancedSearch();
            })
        ];

    }

}
