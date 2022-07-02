<?php

namespace Modules\Report\Traits;

use App\Http\Controllers\FunctionController;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use App\Models\Tenant\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Category;
use Modules\Item\Models\WebPlatform;


/**
 * Trait ReportTrait
 *
 * @package Modules\Report\Traits
 */
trait ReportTrait
{


    /**
     * @param $request
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRecords($request, $model)
    {
        $document_type_id = FunctionController::InArray($request, 'document_type_id');
        $establishment_id = FunctionController::InArray($request, 'establishment_id');
        $period = FunctionController::InArray($request, 'period');
        $date_start = FunctionController::InArray($request, 'date_start');
        $date_end = FunctionController::InArray($request, 'date_end');
        $month_start = FunctionController::InArray($request, 'month_start');
        $month_end = FunctionController::InArray($request, 'month_end');
        $person_id = FunctionController::InArray($request, 'person_id');
        $type_person = FunctionController::InArray($request, 'type_person');

        $seller_id = FunctionController::InArray($request, 'seller_id');
        $state_type_id = FunctionController::InArray($request, 'state_type_id');
        $purchase_order = FunctionController::InArray($request, 'purchase_order');
        $guides = FunctionController::InArray($request, 'guides');
        $web_platform = FunctionController::InArray($request, 'web_platform_id',0);





        $d_start = null;
        $d_end = null;

        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */
        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        $records = $this->data($document_type_id,
            $establishment_id,
            $d_start,
            $d_end,
            $person_id,
            $type_person,
            $model,
            $seller_id,
            $state_type_id,
            $purchase_order,
            $guides,
            $web_platform);
           return $records;

    }


    /**
     * @param      $document_type_id
     * @param      $establishment_id
     * @param      $date_start
     * @param      $date_end
     * @param      $person_id
     * @param      $type_person
     * @param      $model
     * @param      $seller_id
     * @param      $state_type_id
     * @param      $purchase_order
     * @param null $guides
     *
     * @return \App\Models\Tenant\PurchaseItem|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    private function data(
        $document_type_id,
        $establishment_id,
        $date_start,
        $date_end,
        $person_id,
        $type_person,
        $model,
        $seller_id,
        $state_type_id,
        $purchase_order,
        $guides = null,
        $web_platform = null
    ) {
        $web_platform = (int)$web_platform;
        $document_type_id = ($document_type_id == 'null')?null:$document_type_id;
        // En unas vistas esta consultando "01" en vez 01
        $document_type_id = str_replace('"','',$document_type_id);
        if($model !== PurchaseItem::class) {
            $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])
                ->latest()
                 -> whereTypeUser();
        }else{
            $data = PurchaseItem::whereNotNull('id');
        }
        /** @var \Illuminate\Database\Eloquent\Builder  $data */
        if ($document_type_id && $establishment_id) {
            if($document_type_id == '80') {
                $data->where([['establishment_id', $establishment_id]]);
            }
            else {
                $data->where([['establishment_id', $establishment_id], ['document_type_id', $document_type_id]]);
            }
            
        } elseif ($document_type_id) {
            if (in_array($document_type_id, [
                '01',
                '03',
                '07',
                '08',
            ], true)
            ) {
                    $data->where('document_type_id', $document_type_id);
            }else{
                //las notas de venta no tienen document_type_id
                if($model !== SaleNote::class) {
                   $data->where('document_type_id', 'like', '%' . $document_type_id . '%');
                }
            }
        } elseif ($establishment_id) {
            // $data->where('establishment_id', 'like', '%'.$establishment_id.'%');
            $data->where('establishment_id', $establishment_id);
        }


        if($person_id && $type_person){
            $column = ($type_person == 'customers') ? 'customer_id':'supplier_id';
             $data->where($column, $person_id);
        }

        /**
         * Con este filtro se ajsuta reportes por campo user_id y seller_id para modulos que no lo tengan, esto es
         * si el envio de la peticion tiene user_type y user_id se considera qomo parte del filtro de creador/vendedor
         *
         * @var \Illuminate\Http\Request $rquest
         */
        $request = request();
        $userType = ($request !== null && $request->has('user_type') && !empty($request->user_type)) ? $request->user_type : null;
        $userId = ($request !== null && $request->has('user_id') && !empty($request->user_id)) ? $request->user_id : null;

        if (
            !empty($userType) &&
            !empty($userId)
        ) {
            $column = null;
            if ($userType == 'CREADOR') {
                // Quien realiza el documento
                $column = 'user_id';
            } elseif ($userType == 'VENDEDOR') {
                // Vendedor asignado
                $column = 'seller_id';
            }
            if (Str::startsWith($userId, '[')) {
                // Si comienza con [ es un array serialziado por json.
                $usersId = json_decode($userId);
                if (count($usersId) > 0) {
                    $data->whereIn($column, $usersId);
                }
            } else {
                $data->where($column, $userId);
            }
        } elseif ((int)$seller_id != 0) {
            $data->where('user_id', $seller_id);
            // $data->where('seller_id', $seller_id);

        }

        if($state_type_id){
             $data->where('state_type_id', $state_type_id);
        }
        if($purchase_order){
             $data->where('purchase_order', $purchase_order);
        }
        if($model == 'App\Models\Tenant\Document'){
            if(!empty($guides)){
                $data->where('guides','like', DB::raw("%\"number\":\"%").$guides. DB::raw("%\"%"));
            }
        }

        // Se pueden tomar mas filtros de refencia en modules/Report/Http/Controllers/ReportGeneralItemController.php
        //|| $brand_id || $category_id
        if ($web_platform != 0) {
            // , $brand_id, $category_id
            // / ** @var SaleNote $data */
            $data = $data->wherehas('items', function ($a) use ($web_platform) {
                $a->whereHas('relation_item', function ($q) use ($web_platform) {
                    if ($web_platform != 0) {
                        $q->where('web_platform_id', $web_platform);
                    }
                    /*
                    if ($brand_id) {
                        $q->where('brand_id', $brand_id);
                    }
                    if ($category_id) {
                        $q->where('category_id', $category_id);
                    }
                    */
                });
            });
        }
        return $data;

    }


    /**
     * @param $request
     *
     * @return \App\Models\Tenant\Document|\Illuminate\Database\Query\Builder
     */
    public function getRecordsCash($request){

        $document_type_id = FunctionController::InArray($request,'document_type_id');
        $user_id = FunctionController::InArray($request,'user_id');

        $records = $this->dataCash($document_type_id, $user_id);

        return $records;

    }


    /**
     * @param $document_type_id
     * @param $user_id
     *
     * @return \App\Models\Tenant\Document|\Illuminate\Database\Query\Builder
     */
    private function dataCash($document_type_id, $user_id)
    {

        $sale_notes = [];

        switch ($document_type_id) {
            case '01':
                $documents = Document::whereIn('document_type_id',['01','03'])->latest();
                $sale_notes = SaleNote::latest();
                break;

            case '02':
                $documents = Document::whereIn('document_type_id',['01','03'])->latest();
                break;

            case '03':
                $sale_notes = SaleNote::latest();
                break;
        }

        foreach ($sale_notes as $sn) {
            $documents->push($sn);
        }

        $data = $documents;

        return $data;

    }


    /**
     * @param $type
     *
     * @return mixed
     */
    public function getPersons($type){

        $persons = Person::whereType($type)->orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return $persons;

    }


    /**
     * @param $type
     * @param $request
     *
     * @return mixed
     */
    public function getDataTablePerson($type, $request) {

        $persons = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType($type)->orderBy('name')
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                ];
                            });

        return $persons;

    }

    /**
     * @param string                              $str
     * @param \Illuminate\Support\Collection|null $ids
     *
     * @return Item
     */
    public function getItems($str = '', \Illuminate\Support\Collection  $ids = null){

        $items = Item::orderBy('description');
        if($ids!=null){
            $items->wherein('id',$ids);
        }
        $items->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->description}" :$row->description,
            ];
        });

        return $items;

    }


    /**
     * @param $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDataTableItem($request) {

        $items = Item::where('description', 'like', "%{$request->input}%")
            ->orWhere('internal_id', 'like', "%{$request->input}%")
            ->orderBy('description')
            ->get()
            ->transform(function ($row) {
                /** @var Item $row */
                return [
                    'id' => $row->id,
                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->description}" :$row->description,
                'extra'=>$row->getExtraDataFields(),
            ];
        });

        return $items;

    }

    /**
     * @return mixed
     */
    public function getSellers(){

        $persons = User::whereIn('type', ['seller', 'admin'])->orderBy('name')->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'type' => $row->type,
            ];
        });

        return $persons;

    }

    /**
     * @param $document_types
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSeries($document_types)
    {
        $series = Series::wherein('document_type_id', $document_types->pluck('id')->toArray());
        return $series->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getEstablishment()
    {
        $establishment = Establishment::select('id', 'description');
        if (Auth::user()->type !== 'admin') {
            $establishment = $establishment->where('id', Auth::user()->establishment_id);
        }

        return $establishment->get();
    }

    /**
     * @param $request
     *
     * @return array
     */
    public function getDataOfPeriod($request){

        $period = FunctionController::InArray($request,'period');
        $date_start = FunctionController::InArray($request,'date_start');
        $date_end = FunctionController::InArray($request,'date_end');
        $month_start = FunctionController::InArray($request,'month_start');
        $month_end = FunctionController::InArray($request,'month_end');

        $d_start = null;
        $d_end = null;

        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */
        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        return [
            'd_start' => $d_start,
            'd_end' => $d_end
        ];
    }

    /**
     * @param false $is_sale
     *
     * @return \string[][]
     */
    public function getDateRangeTypes($is_sale = false){

        if($is_sale){

            return [
                ['id' => 'date_of_issue', 'description' => 'Fecha emisión'],
            ];

        }

        return [
            ['id' => 'date_of_issue', 'description' => 'Fecha emisión'],
            ['id' => 'delivery_date', 'description' => 'Fecha entrega']
        ];

    }

    /**
     * @return \string[][]
     */
    public function getOrderStateTypes(){

        return [
            ['id' => 'all_states', 'description' => 'Todos'],
            ['id' => 'pending', 'description' => 'Pendiente'],
            ['id' => 'processed', 'description' => 'Procesado'],
        ];

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCIDocumentTypes(){

        return DocumentType::whereIn('id', ['01', '03', '80'])->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });

    }

    /**
     * @param $params
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStateTypesById($params){

        return StateType::whereIn('id', $params)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getWebPlatforms(){

        return WebPlatform::get();

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBrands()
    {
        return Brand::orderBy('name')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCategories()
    {
        return Category::orderBy('name')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $user = auth()->user();
        $persons = User::select('id', 'name', 'type')
                ->orderBy('name');
        if ($user->type === 'admin') {
            $persons = $persons->whereIn('type', ['seller', 'admin'])
                ->get();
        } else {
            $persons = $persons->where('id', $user->id)
                ->get();
        }
        return $persons;
    }
}
