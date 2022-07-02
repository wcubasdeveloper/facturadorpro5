<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;
use App\Http\Resources\Tenant\OrderCollection;


class OrderController extends Controller
{
   
    public function records(Request $request)
    {
        $records = Order::latest();
        return new OrderCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function store(Request $request) {
        $order = Order::findOrFail($request->id);
        $order->status_order_id = $request->status_order_id;
        $order->save();

        return [
            'success' => true,
            'message' => 'Orden actualizada con éxito'
        ];
    }   
    
}
