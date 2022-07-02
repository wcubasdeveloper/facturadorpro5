<?php

namespace Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Item;
use Modules\Item\Models\Category;
use Modules\Inventory\Models\InventoryConfiguration;
use Modules\Restaurant\Http\Resources\ItemCollection;
use App\Models\Tenant\Promotion;
use App\Http\Controllers\Tenant\Api\ServiceController;
use App\Models\Tenant\ConfigurationEcommerce;
use App\Models\Tenant\Order;
use Exception;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Str;
use App\Http\Controllers\Tenant\EmailController;
use App\Mail\Tenant\CulqiEmail;
use Modules\Restaurant\Models\RestaurantConfiguration;



class RestaurantController extends Controller
{
    public function menu($name = null)
    {
        if($name) {
            $name = str_replace('-', ' ', $name);
        }

        $category = Category::where('name', $name)->first();
        $dataPaginate = Item::where([['apply_restaurant', 1], ['internal_id','!=', null]])
                                ->category($category ? $category->id : null)
                                ->paginate(8);
        $configuration = InventoryConfiguration::first();
        $categories = Category::get();
        return view('restaurant::index', ['dataPaginate' => $dataPaginate, 'configuration' => $configuration->stock_control])->with('categories', $categories);
    }

    /*
     * vista privada
     */
    public function list_items()
    {
        return view('restaurant::items.index');
    }

    public function is_visible(Request $request)
    {
        $item = Item::find($request->id);

        if(!$item->internal_id && $request->apply_restaurant){
            return [
                'success' => false,
                'message' =>'Para habilitar la visibilidad, debe asignar un codigo interno al producto',
            ];
        }

        $visible = $request->apply_restaurant == true ? 1 : 0 ;
        $item->apply_restaurant = $visible;
        $item->save();

        return [
            'success' => true,
            'message' => ($visible > 0 )?'El Producto ya es visible en restaurante' : 'El Producto ya no es visible en restaurante',
            'id' => $request->id
        ];

    }

    public function items(Request $request){
        $records = new ItemCollection(Item::where([['apply_restaurant', 1], ['internal_id','!=', null]])->get());
        return [
            'success' => true,
            'data' => $records
        ];
    }

    public function categories(Request $request){
        $records = Category::all();
        return [
            'success' => true,
            'data' => $records
        ];
    }

    public function partialItem($id)
    {
        $record = Item::find($id);
        return view('restaurant::items.partial', compact('record'));
    }


    public function item($id, $promotion_id = null)
    {
        $row = Item::find($id);
        $exchange_rate_sale = $this->getExchangeRateSale();
        $sale_unit_price = ($row->has_igv) ? $row->sale_unit_price : $row->sale_unit_price*1.18;

        $description = $promotion_id ? $this->getDescriptionWithPromotion($row, $promotion_id) : $row->description;

        $record = (object)[
            'id' => $row->id,
            'internal_id' => $row->internal_id,
            'unit_type_id' => $row->unit_type_id,
            'description' => $description,
            // 'description' => $row->description,
            'technical_specifications' => $row->technical_specifications,
            'name' => $row->name,
            'second_name' => $row->second_name,
            'sale_unit_price' => ($row->currency_type_id === 'PEN') ? $sale_unit_price : ($sale_unit_price * $exchange_rate_sale),
            'currency_type_id' => $row->currency_type_id,
            'has_igv' => (bool) $row->has_igv,
            'sale_unit' => $row->sale_unit_price,
            'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
            'currency_type_symbol' => $row->currency_type->symbol,
            'image' =>  $row->image,
            'image_medium' => $row->image_medium,
            'image_small' => $row->image_small,
            'tags' => $row->tags->pluck('tag_id')->toArray(),
            'images' => $row->images,
            'attributes' => $row->attributes ? $row->attributes : [],
            'promotion_id' => $promotion_id,
        ];

        return view('restaurant::items.record', compact('record'));
    }


    private function getExchangeRateSale(){
        $exchange_rate = app(ServiceController::class)->exchangeRateTest(date('Y-m-d'));
        return (array_key_exists('sale', $exchange_rate)) ? $exchange_rate['sale'] : 1;
    }

    public function getDescriptionWithPromotion($item, $promotion_id)
    {
        $promotion = Promotion::findOrFail($promotion_id);
        return "{$item->description} - {$promotion->name}";
    }

    public function detailCart()
    {
        $configuration = ConfigurationEcommerce::first();

        $history_records = [];
        if (auth()->user()) {
            $email_user = auth()->user()->email;
            $history_records = Order::where('apply_restaurant', 1)->where('customer', 'LIKE', '%'.$email_user.'%')
                    ->get()
                    ->transform(function($row) {
                        /** @var  Order $row */
                        return $row->getCollectionData();
                    })->toArray();
        }
        return view('restaurant::cart.detail', compact(['configuration','history_records']));
    }

    public function paymentCash(Request $request)
    {
        $validator = Validator::make($request->customer, [
            'telefono' => 'required|numeric',
            'direccion' => 'required',
            'codigo_tipo_documento_identidad' => 'required|numeric',
            'numero_documento' => 'required|numeric',
            'identity_document_type_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $user = auth()->user();
                $order = Order::create([
                'external_id' => Str::uuid()->toString(),
                'customer' =>  $request->customer,
                'shipping_address' => 'direccion 1',
                'items' =>  $request->items,
                'total' => $request->precio_culqi,
                'reference_payment' => 'efectivo',
                'status_order_id' => 1,
                'purchase' => $request->purchase,
                'apply_restaurant' => 1
              ]);

            $customer_email = $user->email;
            $document = new stdClass;
            $document->client = $user->name;
            $document->product = $request->producto;
            $document->total = $request->precio_culqi;
            $document->items = $request->items;

            $this->paymentCashEmail($customer_email, $document);

            //Mail::to($customer_email)->send(new CulqiEmail($document));
            return [
                'success' => true,
                'order' => $order
            ];

        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }
      }
    }

    public function paymentCashEmail($customer_email, $document)
    {
        try {
            $email = $customer_email;
            $mailable = new CulqiEmail($document);
            $id = (int) $document->id;
            $model = __FILE__.";;".__LINE__;
            $sendIt = EmailController::SendMail($email, $mailable, $id, $model);
        }catch(\Exception $e)
        {
            return true;
        }
    }

}
