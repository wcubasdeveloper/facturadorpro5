<?php

    namespace Modules\Sale\Http\Controllers;

    use App\CoreFacturalo\Helpers\Storage\StorageDocument;
    use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
    use App\CoreFacturalo\Template;
    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Cash;
    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\ChargeDiscountType;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\NoteCreditType;
    use App\Models\Tenant\Catalogs\NoteDebitType;
    use App\Models\Tenant\Catalogs\OperationType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\PaymentCondition;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Series;
    use App\Models\Tenant\TechnicalServiceItem;
    use App\Models\Tenant\User;
    use App\Traits\OfflineTrait;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Modules\BusinessTurn\Models\BusinessTurn;
    use Modules\Finance\Traits\FinanceTrait;
    use Modules\Sale\Http\Requests\TechnicalServiceRequest;
    use Modules\Sale\Http\Resources\TechnicalServiceCollection;
    use Modules\Sale\Models\TechnicalService;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;
    use Mpdf\HTMLParserMode;
    use Mpdf\Mpdf;


    /**
     * Class TechnicalServiceController
     *
     * @package Modules\Sale\Http\Controllers
     * @mixin Controller
     */
    class TechnicalServiceController extends Controller
    {
        use StorageDocument;
        use FinanceTrait;
        use OfflineTrait;

        protected $technical_service;
        protected $company;

        public function index()
        {
            return view('sale::technical-services.index');
        }

        public function columns()
        {
            return [
                'id' => 'Número',
                'customer' => 'Cliente',
                'date_of_issue' => 'Fecha de emisión',
            ];
        }

        public function records(Request $request)
        {
            $records = $this->getRecords($request);

            return new TechnicalServiceCollection($records->paginate(config('tenant.items_per_page')));
        }

        private function getRecords($request)
        {
            if ($request->column == 'customer') {
                $records = TechnicalService::whereHas('person', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->value}%");
                });
            } else {
                $records = TechnicalService::where($request->column, 'like', "%{$request->value}%");
            }

            return $records->whereTypeUser()->latest();
        }

        public function searchCustomers(Request $request)
        {
            $customers = Person::where('number', 'like', "%{$request->input}%")
                ->orWhere('name', 'like', "%{$request->input}%")
                ->whereType('customers')->orderBy('name')
                ->whereIsEnabled()
                ->get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                    ];
                });

            return compact('customers');
        }

        public function tables()
        {
            $customers = $this->table('customers');
            // $prepayment_documents = $this->table('prepayment_documents');
            $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();
            $series = collect(Series::all())->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'contingency' => (bool)$row->contingency,
                    'document_type_id' => $row->document_type_id,
                    'establishment_id' => $row->establishment_id,
                    'number' => $row->number
                ];
            });
            $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
            $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
            $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
            $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
            $currency_types = CurrencyType::whereActive()->get();
            $operation_types = OperationType::whereActive()->get();
            $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
            $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
            $company = Company::active();
            $document_type_03_filter = config('tenant.document_type_03_filter');
            $user = auth()->user()->type;
            $sellers = User::where('establishment_id', auth()->user()->establishment_id)->whereIn('type', ['seller', 'admin'])->orWhere('id', auth()->user()->id)->get();
            $payment_method_types = $this->table('payment_method_types');
            $business_turns = BusinessTurn::where('active', true)->get();
            $enabled_discount_global = config('tenant.enabled_discount_global');
            $is_client = $this->getIsClient();
            $select_first_document_type_03 = config('tenant.select_first_document_type_03');
            $payment_conditions = PaymentCondition::all();

            $document_types_guide = DocumentType::whereIn('id', ['09', '31'])->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'active' => (bool)$row->active,
                    'short' => $row->short,
                    'description' => ucfirst(mb_strtolower(str_replace('REMITENTE ELECTRÓNICA', 'REMITENTE', $row->description))),
                ];
            });
            // $cat_payment_method_types = CatPaymentMethodType::whereActive()->get();
            // $detraction_types = DetractionType::whereActive()->get();

//        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
//                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
//                       'discount_types', 'charge_types', 'company', 'document_type_03_filter',
//                       'document_types_guide');

            // return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
            //                'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
            //                'discount_types', 'charge_types', 'company', 'document_type_03_filter');

            $payment_destinations = $this->getPaymentDestinations();
            $document_id = auth()->user()->document_id;
            $series_id = auth()->user()->series_id;
            $affectation_igv_types = AffectationIgvType::whereActive()->get();

            return compact(
                'document_id',
                'series_id',
                'customers',
                'establishments',
                'series',
                'document_types_invoice',
                'document_types_note',
                'note_credit_types',
                'note_debit_types',
                'currency_types',
                'operation_types',
                'discount_types',
                'charge_types',
                'company',
                'document_type_03_filter',
                'document_types_guide',
                'user',
                'sellers',
                'payment_method_types',
                'enabled_discount_global',
                'business_turns',
                'is_client',
                'select_first_document_type_03',
                'payment_destinations',
                'payment_conditions',
                'affectation_igv_types'
            );

        }

        public function table($table)
        {
            switch ($table) {
                case 'customers':

                    $customers = Person::whereType('customers')->whereIsEnabled()->orderBy('name')->take(20)->get()->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number . ' - ' . $row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    });
                    return $customers;

                    break;
                default:
                    return [];

                    break;
            }
        }

        /**
         * @param int $id
         *
         * @return array
         */
        public function record($id = 0)
        {
            $service = TechnicalService::find($id);
            if ($service == null) $service = new TechnicalService();
            return ['data' => $service->getCollectionData()];
        }

        public function store(TechnicalServiceRequest $request)
        {

            DB::connection('tenant')->transaction(function () use ($request) {

                $data = $this->mergeData($request);
                $tc_id = ($request->has('id'))?$request->id:null;

                $technical_service = TechnicalService::updateOrCreate(['id' => $request->input('id')], $data);
                 $all_item = [];
                foreach ($data['items'] as $row) {
                    /** @var TechnicalServiceItem $temp_item */
                    $temp_item = $technical_service->items()->create($row);
                    $all_item[] = $temp_item->id;

                }
                /* Elimina items del servicio */
                if($tc_id != null) {
                    $items = TechnicalServiceItem::where('technical_services_id',$tc_id)->wherenotin('id',$all_item)->get();
                    /** @var TechnicalServiceItem $temp */
                    foreach($items as $temp){
                        $temp->delete();
                    }
                }
                $this->technical_service = $technical_service;
                $this->setFilename();
                $this->createPdf($this->technical_service, "a4", $this->technical_service->filename);

                $cash = Cash::query()->where([['user_id', auth()->id()], ['state', true]])->first();
                $cash->cash_documents()->create([
                    'technical_service_id' => $this->technical_service->id
                ]);

            });

            return [
                'success' => true,
                'message' => $request->id ? 'Servicio técnico actualizado' : 'Servicio técnico registrado'
            ];

        }

        public function mergeData($inputs)
        {

            $this->company = Company::active();

            $values = [
                'user_id' => auth()->id(),
                'customer' => PersonInput::set($inputs['customer_id']),
                'soap_type_id' => $this->company->soap_type_id,
            ];

            $inputs->merge($values);

            return $inputs->all();
        }

        private function setFilename()
        {

            $name = ['TS', $this->technical_service->id, date('Ymd')];
            $this->technical_service->filename = join('-', $name);
            $this->technical_service->save();

        }

        public function createPdf($technical_service = null, $format_pdf = null, $filename = null)
        {

            ini_set("pcre.backtrack_limit", "5000000");
            $template = new Template();
            $pdf = new Mpdf();

            $document = ($technical_service != null) ? $technical_service : $this->technical_service;
            $company = ($this->company != null) ? $this->company : Company::active();
            $filename = ($filename != null) ? $filename : $this->technical_service->filename;

            $configuration = Configuration::first();

            $base_template = $configuration->formats; //config('tenant.pdf_template');

            $html = $template->pdf($base_template, "technical_service", $company, $document, $format_pdf);

            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $default = [
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                            DIRECTORY_SEPARATOR . 'pdf' .
                            DIRECTORY_SEPARATOR . $base_template .
                            DIRECTORY_SEPARATOR . 'font')
                    ]),
                    'fontdata' => $fontData + [
                            'custom_bold' => [
                                'R' => $pdf_font_bold . '.ttf',
                            ],
                            'custom_regular' => [
                                'R' => $pdf_font_regular . '.ttf',
                            ],
                        ]
                ];

                if ($base_template == 'citec') {
                    $default = [
                        'mode' => 'utf-8',
                        'margin_top' => 2,
                        'margin_right' => 0,
                        'margin_bottom' => 0,
                        'margin_left' => 0,
                        'fontDir' => array_merge($fontDirs, [
                            app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                                DIRECTORY_SEPARATOR . 'pdf' .
                                DIRECTORY_SEPARATOR . $base_template .
                                DIRECTORY_SEPARATOR . 'font')
                        ]),
                        'fontdata' => $fontData + [
                                'custom_bold' => [
                                    'R' => $pdf_font_bold . '.ttf',
                                ],
                                'custom_regular' => [
                                    'R' => $pdf_font_regular . '.ttf',
                                ],
                            ]
                    ];

                }

                $pdf = new Mpdf($default);
            }

            $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                DIRECTORY_SEPARATOR . 'pdf' .
                DIRECTORY_SEPARATOR . $base_template .
                DIRECTORY_SEPARATOR . 'style.css');

            $stylesheet = file_get_contents($path_css);

            $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);


            $this->uploadFile($filename, $pdf->output('', 'S'), 'technical_service');
        }

        public function uploadFile($filename, $file_content, $file_type)
        {
            $this->uploadStorage($filename, $file_content, $file_type);
        }

        public function searchCustomerById($id)
        {
            return $this->searchClientById($id);

        }

        public function toPrint($id, $format)
        {

            $technical_service = TechnicalService::find($id);

            if (!$technical_service) throw new Exception("El código es inválido, no se encontró el servicio técnico relacionado");

            $this->reloadPDF($technical_service, $format, $technical_service->filename);
            $temp = tempnam(sys_get_temp_dir(), 'technical_service');

            file_put_contents($temp, $this->getStorage($technical_service->filename, 'technical_service'));

            return response()->file($temp);
        }

        private function reloadPDF($technical_service, $format, $filename)
        {
            $this->createPdf($technical_service, $format, $filename);
        }

        public function destroy($id)
        {

            $record = TechnicalService::findOrFail($id);

            if ($record->payments()->count() > 0) {
                return [
                    'success' => false,
                    'message' => 'El servicio técnico tiene pagos asociados, debe eliminarlos previamente'
                ];
            }

            $record->delete();

            return [
                'success' => true,
                'message' => 'Servicio técnico eliminado con éxito'
            ];

        }

    }
