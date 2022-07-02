<?php

    namespace Modules\Sale\Http\Controllers;

    use App\CoreFacturalo\Requests\Inputs\DocumentInput;
    use App\Http\Controllers\Tenant\DocumentController;
    use App\Http\Controllers\Tenant\SaleNoteController;
    use App\Models\Tenant\DocumentItem;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\Series;
    use App\Http\Controllers\Controller;
    use Exception;
    use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
    use Illuminate\Database\Events\QueryExecuted;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Modules\Finance\Traits\FinanceTrait;
    use Modules\Sale\Http\Resources\TechnicalServiceResource;
    use Modules\Sale\Models\TechnicalService;

    class GenerateDocumentController extends Controller
    {
        use FinanceTrait;

        public function tables()
        {
            $establishment = Establishment::query()->where('id', auth()->user()->establishment_id)->first();
            $series = Series::query()->where('establishment_id', $establishment->id)->get();
            $document_types = [
                ['id' => '01', 'name' => 'Factura'],
                ['id' => '03', 'name' => 'Boleta'],
                ['id' => 'nv', 'name' => 'Nota de venta'],
            ];
            $payment_method_types = PaymentMethodType::all();
            $payment_destinations = $this->getPaymentDestinations();

            return compact('series', 'document_types', 'payment_method_types', 'payment_destinations', 'establishment');
        }

        public function record($table, $id)
        {
            if ($table === 'technical-services') {
                return new TechnicalServiceResource(TechnicalService::query()->findOrFail($id));
            }
        }

        public function customers(Request $request)
        {
            $customers = Person::query()->where('number', 'like', "%{$request->input}%")
                ->orWhere('name', 'like', "%{$request->input}%")
                ->whereType('customers')
                ->whereIsEnabled()
                ->orderBy('name')
                ->get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code,
                        'addresses' => $row->addresses,
                        'address' => $row->address
                    ];
                });

            return compact('customers');
        }

        public function store(Request $request)
        {
            DB::connection('tenant')->beginTransaction();
            try {
                $inputs = $request->all();
                $items = $request->input('items');
                foreach ($items as $index => $item) {
                    $unit_type = $item['item']['unit_type_id']??'ZZ';
                    if (isset($item['additional_information']) && is_array($item['additional_information'])) {
                        $item['additional_information'] = implode(' ', $item['additional_information']);
                    }
                    if (isset($inputs['items'][$index]['item']['warehouses'])) {
                        unset($inputs['items'][$index]['item']['warehouses']);
                    }
                    if (!isset($item['item_id'])) {
                        $internal = $item['internal_id'];
                        $newItem = !empty($internal) ? Item::where('internal_id', $internal)->first() : null;
                        $inputs['items'][$index]['item_id'] = ($newItem === null) ? $this->storeItem($item) : $newItem->id;
                    }
                    $tempItem = $inputs['items'][$index];
                    if (isset($tempItem['additional_information']) && is_array($tempItem['additional_information'])) {
                        $tempItem['additional_information'] = implode(' ', $tempItem['additional_information']);
                    }
                    $a = new DocumentItem();
                    $a->fill($tempItem);
                    $inputs['items'][$index] = $a->toArray();
                    $inputs['items'][$index]['item'] = (array)$a->getArrayItem();
                    $inputs['items'][$index]['item']['unit_type_id']  = !empty($unit_type)?$unit_type:'ZZ';
                    if (isset($inputs['items'][$index]['additional_information'])) {
                        $inputs['items'][$index]['additional_information'] = implode(' ', $inputs['items'][$index]['additional_information']);
                    }
                }
                //$inputs['items'][0]['item_id'] = $this->storeItem($request->input('items')[0]);
                if (in_array($request->input('document_type_id'), ['01', '03'])) {
                    $documentController = new DocumentController();
                    $doc_input = DocumentInput::set($inputs);
                    $res = $documentController->storeWithData($doc_input);
                } else {
                    $inputs['items'] = DocumentInput::items($inputs);
                    $inputs['type_period'] = null;
                    $inputs['quantity_period'] = null;
                    $res = (new SaleNoteController())->storeWithData($inputs);
                }

                DB::connection('tenant')->commit();
                return $res;

            } catch (Exception $e) {
                DB::connection('tenant')->rollBack();
                return [
                    'success' => false,
                    'message' => $e->getFile() . '-' . $e->getLine() . '-' . $e->getMessage()
                ];
            }


        }

        /**
         * Normalmente, los items no registrados son parte del servicio, por ello unit_type_id es ZZ
         *
         * @param $row
         *
         * @return HigherOrderBuilderProxy|mixed
         */
        public function storeItem($row)
        {

            $internal_id = $row['internal_id'] ?? ($row['item']['internal_id'] ?? null);
            $description = $row['description'] ?? ($row['item']['description'] ?? null);
            $item_type_id = $row['item_type_id'] ?? ($row['item']['item_type_id'] ?? null);
            $second_name = $row['second_name'] ?? ($row['item']['second_name'] ?? null);
            $name = $row['name'] ?? ($row['item']['name'] ?? null);
            $unit_type_id = $row['unit_type_id'] ?? ($row['item']['unit_type_id'] ?? 'ZZ');
            $currency_type_id = $row['currency_type_id'] ?? ($row['item']['currency_type_id'] ?? 'PEN');
            $unit_price = $row['unit_price'] ?? ($row['item']['unit_price'] ?? null);
            $affectation_igv_type_id = $row['affectation_igv_type_id'] ?? ($row['item']['affectation_igv_type_id'] ?? null);
            if(empty($unit_type_id)) $unit_type_id = 'ZZ';
            $data = [
                'internal_id' => $internal_id,
                'description' => $description,
                'name' => $name,
                'second_name' => $second_name,
                'item_type_id' => $item_type_id,
                'unit_type_id' => $unit_type_id,
                'currency_type_id' => $currency_type_id,
                'sale_unit_price' => $unit_price,
                'sale_affectation_igv_type_id' => $affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $affectation_igv_type_id,
                'stock' => 0,
                'attributes' => (!empty($row['attributes'])) ? $row['attributes'] : [],
                'discounts' => (!empty($row['discounts'])) ? $row['discounts'] : [],
                'charges' => (!empty($row['charges'])) ? $row['charges'] : [],
                'extra' => (!empty($row['extra'])) ? $row['extra'] : [],
                'colors' => (!empty($row['colors'])) ? $row['extra'] : [],
                'sale_affectation_igv_type' => (!empty($row['sale_affectation_igv_type'])) ? $row['sale_affectation_igv_type'] : [],
                'CatItemUnitsPerPackage' => (!empty($row['CatItemUnitsPerPackage'])) ? $row['CatItemUnitsPerPackage'] : [],
                'CatItemMoldProperty' => (!empty($row['CatItemMoldProperty'])) ? $row['CatItemMoldProperty'] : [],
                'CatItemProductFamily' => (!empty($row['CatItemProductFamily'])) ? $row['CatItemProductFamily'] : [],
                'CatItemMoldCavity' => (!empty($row['CatItemMoldCavity'])) ? $row['CatItemMoldCavity'] : [],
                'CatItemPackageMeasurement' => (!empty($row['CatItemPackageMeasurement'])) ? $row['CatItemPackageMeasurement'] : [],
                'CatItemStatus' => (!empty($row['CatItemStatus'])) ? $row['CatItemStatus'] : [],
                'CatItemSize' => (!empty($row['CatItemSize'])) ? $row['CatItemSize'] : [],
                'CatItemUnitBusiness' => (!empty($row['CatItemUnitBusiness'])) ? $row['CatItemUnitBusiness'] : [],
                'warehouses' => (!empty($row['warehouses'])) ? $row['warehouses'] : [],
                'item_unit_types' => (!empty($row['item_unit_types'])) ? $row['item_unit_types'] : [],
                'presentation' => (!empty($row['presentation'])) ? $row['presentation'] : [],
            ];
            $item = Item::query()->create($data);
            return $item->id;
        }
//    private function searchCustomers($input)
//    {
//        return  Person::query()->where('number','like', "%{$input}%")
//        ->orWhere('name','like', "%{$input}%")
//        ->whereType('customers')
//        ->whereIsEnabled()
//        ->orderBy('name')
//        ->get()->transform(function($row) {
//            return [
//                'id' => $row->id,
//                'description' => $row->number.' - '.$row->name,
//                'name' => $row->name,
//                'number' => $row->number,
//                'identity_document_type_id' => $row->identity_document_type_id,
//                'identity_document_type_code' => $row->identity_document_type->code,
//                'addresses' => $row->addresses,
//                'address' =>  $row->address
//            ];
//        });
//    }
    }
