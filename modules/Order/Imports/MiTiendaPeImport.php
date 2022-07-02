<?php


    namespace Modules\Order\Imports;


    use App\CoreFacturalo\Requests\Inputs\DocumentInput;
    use App\Http\Controllers\Tenant\Api\ServiceController;
    use App\Http\Controllers\Tenant\DocumentController;
    use App\Http\Requests\Tenant\DocumentRequest;

    // use App\Models\System\PaymentMethodType;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\ExchangeRate;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonAddress;
    use App\Models\Tenant\Series;
    use App\Models\Tenant\User;
    use App\Models\Tenant\Warehouse;
    use Auth;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;
    use Log;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;

    use Modules\ApiPeruDev\Data\ServiceData;
    use Modules\Order\Http\Controllers\OrderNoteController;
    use Modules\Order\Http\Requests\OrderNoteRequest;
    use Modules\Order\Models\ConfigurationMiTiendaPe;
    use Modules\Order\Models\MiTiendaPe;
    use Modules\Order\Models\OrderNote;
    use Modules\Order\Models\OrderNoteItem;
    use SoapFault;


    class MiTiendaPeImport implements ToCollection
    {
        use Importable;

        /** @var array */
        protected $process;
        protected $data;
        /** @var ConfigurationMiTiendaPe */
        protected $configuration;
        /** @var User */
        protected $user;

        /**
         * @return array
         */
        public function getProcess(): array
        {
            return $this->process;
        }

        /**
         * @param array $process
         *
         * @return MiTiendaPeImport
         */
        public function setProcess(array $process): MiTiendaPeImport
        {
            $this->process = $process;
            return $this;
        }

        public function collection(Collection $rows)
        {
            libxml_disable_entity_loader(false);
            $this->process = [];
            $configurationMiTienda = ConfigurationMiTiendaPe::first();
            if (empty($configurationMiTienda)) {
                $configurationMiTienda = new ConfigurationMiTiendaPe();
            }
            $this->configuration = $configurationMiTienda;

            $exchangeRate = ExchangeRate::where('date', Carbon::now()->format('Y-m-d'))->first();
            if (empty($exchangeRate)) {
                $exchangeRate = ExchangeRate::where('date', '<', Carbon::now()->format('Y-m-d'))->orderBy('date', 'desc')->first();
            }
            $exchangeRateValue = $exchangeRate->sale_original;
            $currentUser = Auth::user();
            if (empty($currentUser)) {
                $currentUser = new User();
            }
            $this->user = $currentUser;
            $warehouse = Warehouse::where('establishment_id', $currentUser->establishment_id)->first();
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            $orders = [];
            $numberOrders = [];
            $items = [];
            $order_total = [];
            $order_total_discount = [];
            $order_total_taxes = [];
            $order_unit_value = [];
            $order_total_base_igv = [];
            $order_percentage_igv = [];
            $order_total_igv = [];
            $order_discounts = [];
            $order_unit_price = [];
            $order_total_value = [];

            $order_total_taxed = [];
            $order_total_exonerated = [];

            foreach ($rows as $row) {
                $documentType = $row[0] ?? null;
                $miTiendaPeOrder = $row[1] ?? null;
                $names = $row[2] ?? null;
                $lastnames = $row[3] ?? null;
                $phone = $row[4] ?? null;
                $street = $row[5] ?? null;
                $region = $row[6] ?? null;
                $district = $row[7] ?? null;
                $paymentType = $row[8] ?? 'Contado';
                $paymentType = trim($paymentType);
                $payment_method_types = PaymentMethodType::where('description', $paymentType)->first();
                if (empty($payment_method_types)) {
                    $payment_method_types = PaymentMethodType::where('description', 'Contado')->first();
                }


                $identificationNumber = $row[9] ?? null;
                $email = $row[10] ?? null;

                $internal_id = trim($row[11] ?? null);
                $quantity = $row[12] ?? null;
                // $amountWithOutIGV = $row[13] ?? 0; // precio sin igv
                $amountWithOutIGV = $row[17] ?? 0; // precio sin igv
                //
                $total_igv = $row[14] ?? 0; // igv del producto
                $igv = $row[15] ?? 18; // igv


                if (!is_numeric($igv)) {
                    $igv = 18;
                }
                $discount = $row[16] ?? null;
                // $total = $row[13]??null;
                $stotal = $row[17] ?? null;
                $shipping = $row[18] ?? null;
                $totalImport = $row[19] ?? null;
                $reference = $row[20] ?? null;
                $saleDate = $row[21] ?? null;
                $transaction_code = $row[22] ?? null;

                // $totalImport -=$discount;


                if (empty($identificationNumber)) {
                    continue;
                    // si no hay numero de idtenficacion de cliente, se continua
                }
                if (!empty($saleDate)) {
                        $saleDate = Carbon::createFromFormat('d/m/Y', $saleDate)->format('Y-m-d');
                }


                $documentType = ucfirst($documentType);
                if ($documentType == 'Factura') {
                    $documentType = DocumentType::where('short', 'FT')->first();
                } elseif ($documentType == 'Boleta') {
                    $documentType = DocumentType::where('short', 'BV')->first();
                }
                $names = trim($names);
                $email = trim($email);
                $customer = Person::SearchCustomer($identificationNumber, $names, $email)->first();
                $customer = $this->setCustomer($customer,
                    $lastnames,
                    $names,
                    $identificationNumber,
                    $street,
                    $email,
                    $phone,
                    $region,
                    $district
                );

                // Clientes
                /*
                if (empty($customer)) {
                    $address = [
                        'country_id'=>'PE',
                    ];
                    $dataCustomer = [
                        'type' => 'customers',
                        'name' => "$lastnames, $names",
                        'number' => $identificationNumber,
                        'country_id' => 'PE',
                        'address' => $street,
                        'email' => $email,
                        'telephone' => $phone,
                    ];
                    if (strlen($identificationNumber) === 8) {
                        $dataCustomer = self::getPersonData($identificationNumber, $dataCustomer);

                        $address['department_id'] = $dataCustomer['department_id']??0;
                        $address['province_id'] = $dataCustomer['province_id']??0;
                        $address['district_id'] = $dataCustomer['district_id']??0;

                    } elseif (strlen($identificationNumber) === 11) {
                        $dataCustomer = self::getPersonData($identificationNumber, $dataCustomer);
                        $dataCustomer['name']= $dataCustomer['name']??$dataCustomer['name'];
                        $dataCustomer['address']= $dataCustomer['address']??$dataCustomer['address'];
                        $dataCustomer['trade_name']= $dataCustomer['trade_name']??null;
                        $address['department_id'] = $dataCustomer['department_id']??0;
                        $address['province_id'] = $dataCustomer['province_id']??0;
                        $address['district_id'] = $dataCustomer['district_id']??0;

                    }
                    $address['address'] = $dataCustomer['address']??null;

                    // Crear nuevo cliente
                    $customer = new Person($dataCustomer);
                    $customer->identity_document_type_id = IdentityDocumentType::where('description', 'Doc.trib.no.dom.sin.ruc')->first()->id;
                    if (strlen($identificationNumber) == 11) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'RUC')->first()->id;
                    } elseif (strlen($identificationNumber) == 8) {
                        $customer->identity_document_type_id = IdentityDocumentType::where('description', 'DNI')->first()->id;
                    }
                    $province = Department::where('description', $region)->first();
                    if (!empty($province)) {
                        $customer->department_id = $province->id;
                    }
                    $district = District::where('description', $district)->first();
                    if (!empty($district)) {
                        $customer->district_id = $district->id;
                        $customer->province_id = $district->province_id;
                    }
                    $customer->contact = json_decode(json_encode(["full_name" => $customer->name, "phone" => $customer->telephone,]));
                    $customer->push();

                    $address['person_id'] = $customer->id;
                    $address['department_id'] = !empty($address['department_id'])?$address['department_id']:$customer->department_id;
                    $address['province_id'] = !empty($address['province_id'])?$address['province_id']:$customer->province_id;
                    $address['district_id'] = !empty($address['district_id'])?$address['district_id']:$customer->district_id;


                    $addressPerson = new PersonAddress($address);
                    $addressPerson->push();

                }
                */


                $customerData = $customer->getCollectionData();
                $customerData['apellidos_y_nombres_o_razon_social'] = "$lastnames, $names";
                $customerData['telefono'] = $phone;
                $customerData['correo_electronico'] = $email;
                $customerData['direccion'] = "$street, $district, $region";
                $payment_method_type_id = empty($payment_method_types) ? '10' : $payment_method_types->id;
                $temp = [
                    "prefix" => "PD", // nota, usar una configuracion para este caso CMAR
                    'transaction_code' => $transaction_code,
                    'exchange_rate_sale' => $exchangeRateValue,
                    'sale_original' => $exchangeRateValue,
                    'external_id' => Str::uuid()->toString(),
                    'customer_id' => $customerData['id'],
                    'customer' => $customerData,
                    "reference_payment" => empty($payment_method_types) ? 'Contado' : $payment_method_types->description,
                    'establishment_id' => $this->configuration->establishment_id,
                    'user_id' => $this->user->id,
                    'currency_type_id' => $this->configuration->currency_type_id ?? 'PEN',
                    "purchase_order" => $miTiendaPeOrder,
                    'date_of_issue' => $saleDate,
                    'time_of_issue' => Carbon::now()->format('H:i:s'),
                    // 'purchase'
                    "operation_type_id" => null,
                    "shipping_address" => $street,
                    "date_of_due" => $saleDate,
                    "delivery_date" => null,
                    "charges" => [],
                    "attributes" => [],
                    "guides" => [],
                    "payment_method_type_id" => $payment_method_type_id,
                    "additional_information" => null,
                    "actions" => [
                        "format_pdf" => "a4",
                    ],

                    "discounts" => [],
                    "total_prepayment" => 0,
                    "total_charge" => 0,
                    "total_discount" => (float)$discount,
                    "total_exportation" => 0,
                    "total_free" => 0,
                    "total_taxed" => 0,
                    "total_unaffected" => 0,
                    "total_igv_free" => 0,
                    "total_igv" => 0,
                    "total_base_isc" => 0,
                    "total_isc" => 0,
                    "total_base_other_taxes" => 0,
                    "total_other_taxes" => 0,
                    "total_taxes" => 0,
                    'total' => $totalImport,

                    'total_exonerated' => 0,
                    "document_type_id" => $documentType,
                    // "payment_method" => $payment_method_types,
                    "total_value" => $totalImport,
                ];
                $observation = null;
                if ($customer->name != "$lastnames, $names") {
                    $observation = "$lastnames, $names";
                }

                $item = Item::where('internal_id', $internal_id)->first();

                if (!empty($item)) {

                    $total_igv = $row[14] ?? 0;
                    if (!isset($orders[$miTiendaPeOrder])) $orders[$miTiendaPeOrder] = [];
                    $numberOrders[] = $miTiendaPeOrder;
                    if ($quantity <= 0) $quantity = 1;

                    $cItem = $item->getDataToItemModal($warehouse, false, false);
                    $cItem['has_igv'] = $cItem['has_igv'];
                    $cItem['lots'] = (array)$cItem['lots'];

                    $cItem['quantity'] = $quantity;
                    $totalItem = $amountWithOutIGV + $total_igv;
                    $unit_price = ($totalItem / $quantity) - $discount;
                    $itemTo = [
                        'item_id' => $item->id,
                        'item' => (array)$cItem,
                        'observation' => $observation,
                        'quantity' => $quantity,
                        'currency_type_id' => 'PEN',
                        'affectation_igv_type_id' => $item->sale_affectation_igv_type_id,
                        'affectation_igv_type' => $item->sale_affectation_igv_type,
                        'total_base_isc' => 0,
                        'percentage_isc' => 0,
                        'total_isc' => 0,
                        'total_base_other_taxes' => 0,
                        'percentage_other_taxes' => 0,
                        'total_other_taxes' => 0,
                        'total_plastic_bag_taxes' => 0,
                        'attributes' => [],
                        'discounts' => [],
                        'charges' => [],
                        'price_type_id' => '01',
                        'total_charge' => 0,
                        'total_exonerated' => empty($total_igv) ? ($totalItem - $discount) : 0,
                        'total_base_igv' => ($amountWithOutIGV - $discount),
                        'total_igv' => $total_igv,
                        'total_taxed' => !empty($total_igv) ? ($amountWithOutIGV - $discount) : 0,
                        'unit_price' => $unit_price,
                        'total_value' => $amountWithOutIGV - $discount,
                        'total_taxes' => $total_igv,
                        'unit_value' => ($amountWithOutIGV / $quantity),
                        'percentage_igv' => $igv,
                        'total_discount' => (float)$discount,
                        'total' => $unit_price * $quantity,
                        'transaction_code' => $transaction_code,
                    ];

                    $itemTo['unit_type_id'] = $cItem['unit_type_id'];


                    if ($discount != 0) {


                        $ds = [
                            "discount_type_id" => "00",
                            "description" => "",
                            "factor" => round((($temp['total_discount'] * 100) / $itemTo['unit_value']) / 100, 2),
                            "amount" => $temp['total_discount'],
                            "base" => $itemTo['unit_value'],
                        ];
                        $itemTo['discounts'][] = $ds;
                    }

                    if (isset($orders[$miTiendaPeOrder])) {
                        // se busca los item de la orden, para sumar sus totales
                        $checkfield = [
                            'total',
                            'total_discount',
                            'total_taxes',
                            'unit_value',
                            'total_base_igv',
                            'percentage_igv',
                            'total_igv',
                            'unit_price',
                            'total_taxed',
                            'total_value',
                            'total_exonerated'
                        ];

                        foreach ($checkfield as $field) {
                            if (isset($orders[$miTiendaPeOrder][$field])) {
                                $orders[$miTiendaPeOrder][$field] += $itemTo[$field];
                            }
                        }


                    }
                    $orders[$miTiendaPeOrder] = $temp;
                    if (!isset($orders[$miTiendaPeOrder]['items'])) $orders[$miTiendaPeOrder]['items'] = [];
                    if (!isset($items[$miTiendaPeOrder])) $items[$miTiendaPeOrder] = [];
                    if (!isset($order_total[$miTiendaPeOrder])) $order_total[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_discount[$miTiendaPeOrder])) $order_total_discount[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_taxes[$miTiendaPeOrder])) $order_total_taxes[$miTiendaPeOrder] = 0;
                    if (!isset($order_unit_value[$miTiendaPeOrder])) $order_unit_value[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_base_igv[$miTiendaPeOrder])) $order_total_base_igv[$miTiendaPeOrder] = 0;
                    if (!isset($order_percentage_igv[$miTiendaPeOrder])) $order_percentage_igv[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_igv[$miTiendaPeOrder])) $order_total_igv[$miTiendaPeOrder] = 0;
                    if (!isset($order_unit_price[$miTiendaPeOrder])) $order_unit_price[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_value[$miTiendaPeOrder])) $order_total_value[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_taxed[$miTiendaPeOrder])) $order_total_taxed[$miTiendaPeOrder] = 0;
                    if (!isset($order_total_exonerated[$miTiendaPeOrder])) $order_total_exonerated[$miTiendaPeOrder] = 0;
                    if (!isset($order_discounts[$miTiendaPeOrder])) $order_discounts[$miTiendaPeOrder] = [];

                    $items[$miTiendaPeOrder][] = $itemTo;
                    $order_total[$miTiendaPeOrder] += $itemTo['total'];
                    $order_total_discount[$miTiendaPeOrder] += $itemTo['total_discount'];
                    $order_total_taxes[$miTiendaPeOrder] += $itemTo['total_taxes'];
                    $order_unit_value[$miTiendaPeOrder] += $itemTo['unit_value'];
                    $order_total_base_igv[$miTiendaPeOrder] += $itemTo['total_base_igv'];
                    $order_percentage_igv[$miTiendaPeOrder] += $itemTo['percentage_igv'];
                    $order_total_igv[$miTiendaPeOrder] += $itemTo['total_igv'];
                    $order_unit_price[$miTiendaPeOrder] += $itemTo['unit_price'];
                    $order_total_value[$miTiendaPeOrder] += $itemTo['total_value'];
                    if (!isset($ds)) {
                        $ds = [];
                    }
                    $order_discounts[$miTiendaPeOrder][] = $ds;
                    $orders[$miTiendaPeOrder]['items'][] = $itemTo;


                    $order_total_taxed[$miTiendaPeOrder] += $itemTo['total_taxed'];
                    $order_total_exonerated[$miTiendaPeOrder] += $itemTo['total_exonerated'];
                    /*
                    if($order_total_igv[$miTiendaPeOrder] == 5.19){
                        dd([
                            $row,
                            $itemTo
                        ]);
                    }
                    */


                }

            }
            $numberOrders = array_unique($numberOrders);
            foreach ($numberOrders as $item) {
                $checkItem = MiTiendaPe::where('order_number', $item)->first();
                if (isset($orders[$item]) && empty($checkItem)) {
                    $toSave = $orders[$item];
                    $request = new OrderNoteRequest();
                    $toSave['items'] = $items[$item];
                    $toSave['total'] = $order_total[$item];
                    $toSave['total_discount'] = $order_total_discount[$item];
                    $toSave['total_taxes'] = $order_total_taxes[$item];
                    $toSave['unit_value'] = $order_unit_value[$item];
                    $toSave['total_base_igv'] = $order_total_base_igv[$item];
                    $toSave['percentage_igv'] = $order_percentage_igv[$item];
                    $toSave['total_igv'] = $order_total_igv[$item];
                    $toSave['unit_price'] = $order_unit_price[$item];
                    $toSave['total_value'] = $order_total_value[$item];
                    $toSave['total_taxed'] = $order_total_taxed[$item];
                    $toSave['total_exonerated'] = $order_total_exonerated[$item];
                    $toSave['discounts'] = $order_discounts[$item];


                    $request->merge($toSave);
                    $orderNotecontroller = new OrderNoteController();
                    $result = $orderNotecontroller->store($request);
                    if (isset($result['success']) && $result['success'] === true) {
                        $order = $orderNotecontroller->getOrderNote();
                        $orderId = $order->id;
                        $document = new Document();

                        if ($configurationMiTienda->getAutogenerate() === true && isset($toSave['document_type_id']) && !empty($toSave['document_type_id'])) {
                            $document = $this->setDocument($toSave, $order);
                        }

                        $miTiendaPe = new MiTiendaPe([
                            'order_number' => $item,
                            'transaction_code' => $toSave['transaction_code'],
                            'order_note_id' => $orderId,
                            'document_id' => $document->id,
                        ]);
                        $miTiendaPe->push();
                        $total++;
                        $temp = [
                            'order' => $order,
                            'document' => $document,
                            'data' => $toSave,

                        ];
                        $this->process[] = $temp;
                    } else {
                        Log::debug(" No se pudo guardar por que no fue exitoso \n" . json_encode($toSave));
                    }
                }


            }
            $this->data = compact('total', 'registered');

        }

        protected function setCustomer(
            $customer,
            $lastnames,
            $names,
            $identificationNumber,
            $street,
            $email,
            $phone,
            $region,
            $district

        )
        {
            $cus = Person::where([
                'number'=>$identificationNumber,
                'type'=>'customers',

            ])->first();
            /** @var Person $customer */
            if (empty($customer) && !empty($cus) ) {
                $customer = $cus;
            }

            if (empty($customer) ) {
                $address = [
                    'country_id' => 'PE',
                ];
                $dataCustomer = [
                    'type' => 'customers',
                    'name' => "$lastnames, $names",
                    'number' => $identificationNumber,
                    'country_id' => 'PE',
                    'address' => $street,
                    'email' => $email,
                    'telephone' => $phone,
                ];
                $dataCustomerTemp = $dataCustomer;
                if (strlen($identificationNumber) === 8) {
                    $dataCustomer = self::getPersonData($identificationNumber, $dataCustomer);

                    $address['department_id'] = $dataCustomer['department_id'] ?? 0;
                    $address['province_id'] = $dataCustomer['province_id'] ?? 0;
                    $address['district_id'] = $dataCustomer['district_id'] ?? 0;

                } elseif (strlen($identificationNumber) === 11) {
                    $dataCustomer = self::getPersonData($identificationNumber, $dataCustomer);
                    $dataCustomer['name'] = $dataCustomer['name'] ?? $dataCustomerTemp['name'];
                    $dataCustomer['address'] = $dataCustomer['address'] ?? $dataCustomerTemp['address'];
                    $dataCustomer['trade_name'] = $dataCustomer['trade_name'] ?? null;
                    $address['department_id'] = $dataCustomer['department_id'] ?? 0;
                    $address['province_id'] = $dataCustomer['province_id'] ?? 0;
                    $address['district_id'] = $dataCustomer['district_id'] ?? 0;

                }
                $address['address'] = $dataCustomer['address'] ?? null;

                // Crear nuevo cliente
                $customer = new Person($dataCustomer);
                $customer->identity_document_type_id = IdentityDocumentType::where('description', 'Doc.trib.no.dom.sin.ruc')->first()->id;
                if (strlen($identificationNumber) == 11) {
                    $customer->identity_document_type_id = IdentityDocumentType::where('description', 'RUC')->first()->id;
                } elseif (strlen($identificationNumber) == 8) {
                    $customer->identity_document_type_id = IdentityDocumentType::where('description', 'DNI')->first()->id;
                }
                $province = Department::where('description', $region)->first();
                if (!empty($province)) {
                    $customer->department_id = $province->id;
                }
                $district = District::where('description', $district)->first();
                if (!empty($district)) {
                    $customer->district_id = $district->id;
                    $customer->province_id = $district->province_id;
                }
                $contactArray =[
                    "full_name" => $customer->name,
                    "phone" => $customer->telephone,
                    ];
                $customer->contact = json_decode(json_encode($contactArray));
                $customerTemp = Person::where($dataCustomer)->first();
                if (empty($customerTemp)) {
                    $customer->push();
                } else {
                    $customer = $customerTemp;
                }

                $address['person_id'] = $customer->id;
                $address['department_id'] = !empty($address['department_id']) ? $address['department_id'] : $customer->department_id;
                $address['province_id'] = !empty($address['province_id']) ? $address['province_id'] : $customer->province_id;
                $address['district_id'] = !empty($address['district_id']) ? $address['district_id'] : $customer->district_id;


                $addressPerson = PersonAddress::where($address)->first();
                if (empty($addressPerson)) {
                    // no hay direccion por lo que se crea una
                    $addressPerson = new PersonAddress($address);
                    $addressPerson->push();
                }

            }
            return $customer;
        }

        public static function getPersonData($number = null, $data = [])
        {
            $service = new ServiceData();
            if (!empty($number)) {
                $ruc = ['success' => false];
                if (strlen($number) == 8) {
                    $ruc = $service->service('dni', $number);
                } else if (strlen($number) == 11) {
                    $ruc = $service->service('ruc', $number);
                }
                if ($ruc['success'] == true) {
                    $temp = $ruc['data'];
                    if (isset($data["name"])) {
                        $data["name"] = !empty($temp["name"] && isset($temp["name"])) ? $temp["name"] : $data["name"];
                    }
                    if (isset($data["trade_name"])) {
                        $data["trade_name"] = !empty($temp["trade_name"] && isset($temp["trade_name"])) ? $temp["trade_name"] : $data["trade_name"];
                    }
                    if (isset($data["location_id"])) {
                        $data["location_id"] = !empty($temp["location_id"] && isset($temp["location_id"])) ? $temp["location_id"] : $data["location_id"];
                    }
                    if (isset($data["address"])) {
                        $data["address"] = !empty($temp["address"] && isset($temp["address"])) ? $temp["address"] : $data["address"];
                    }
                    if (isset($data["department_id"])) {
                        $data["department_id"] = !empty($temp["department_id"] && isset($temp["department_id"])) ? $temp["department_id"] : $data["department_id"];
                    }
                    if (isset($data["province_id"])) {
                        $data["province_id"] = !empty($temp["province_id"] && isset($temp["province_id"])) ? $temp["province_id"] : $data["province_id"];
                    }
                    if (isset($data["district_id"])) {
                        $data["district_id"] = !empty($temp["district_id"] && isset($temp["district_id"])) ? $temp["district_id"] : $data["district_id"];
                    }
                    if (isset($data["condition"])) {
                        $data["condition"] = !empty($temp["condition"] && isset($temp["condition"])) ? $temp["condition"] : $data["condition"];
                    }
                    if (isset($data["state"])) {
                        $data["state"] = !empty($temp["state"] && isset($temp["state"])) ? $temp["state"] : $data["state"];
                    }

                }
            }

            return $data;

        }

        /**
         * Genera los datos para documento.
         *
         * @param array          $data
         * @param OrderNote|null $orderNote
         * @param User|null      $user
         *
         * @return Document
         */
        public function setDocument($data = [], OrderNote $orderNote = null): Document
        {

            /** @var DocumentType $documentType */
            $documentType = $data['document_type_id'];

            $data["order_note_id"] = $orderNote->id;
            $data["document_type_id"] = $documentType->id;
            if ($data["document_type_id"] == '03') {
                $data["series_id"] = $this->configuration->series_document_bt_id;
                $data["series"] = Series::find($data["series_id"])->number;
            } elseif ($data["document_type_id"] == '01') {
                $data["series_id"] = $this->configuration->series_document_ft_id;
                $data["series"] = Series::find($data["series_id"])->number;
            } else {
                return new Document();

            }
            $data["establishment_id"] = $this->configuration->establishment_id;
            $data["number"] = "#";
            $data["operation_type_id"] = '0101';
            $data["prefix"] = null;
            $data['type'] = 'invoice';
            $data["payments"][] = [
                "id" => null,
                "document_id" => null,
                "date_of_payment" => $data['date_of_issue'],
                "payment_method_type_id" => $data['payment_method_type_id'],
                "reference" => $data['transaction_code'] ?? null,
                "payment_destination_id" => $this->configuration->payment_destination_id,
                "payment" => $data['total'],
            ];

            $items = $data['items'];
            $newItems = [];
            foreach ($items as $item) {
                $item['item'] = (array)$item['item'];
                $item['item']['lots'] = (array)$item['item']['lots'];

                $newItems[] = $item;
            }
            $data['items'] = $newItems;
            if (isset($data['discounts'])) unset($data['discounts']);

            $data = DocumentInput::set($data);
            $documentController = new DocumentController();

            $documentRequest = new DocumentRequest();
            $documentRequest->merge($data);
            try {
                $request = $documentController->store($documentRequest);
            } catch (SoapFault $e) {
                $request = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
            $document = new Document();
            if (isset($request['success']) && $request['success'] == true) {
                $document = Document::find($request['data']['id']);
            }
            return $document;

        }

        public function getData()
        {
            return $this->data;
        }

    }
