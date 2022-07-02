<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\PurchaseSettlementCollection;
use App\Models\Tenant\PurchaseSettlement;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseSettlementController extends Controller
{

    public function index()
    {
        return view('tenant.purchase-settlements.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = PurchaseSettlement::where($request->column, 'like', "%{$request->value}%")
                            ->where('user_id', auth()->id())
                            ->latest();

        return new PurchaseSettlementCollection($records->paginate(config('tenant.items_per_page')));
    }

}