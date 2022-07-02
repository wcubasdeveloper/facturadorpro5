<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\ConfigurationRequest;
use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\Catalogs\{
    AffectationIgvType,
    ChargeDiscountType
};
use GuzzleHttp\Client;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\CoreFacturalo\Template;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\FormatTemplate;
use Modules\LevelAccess\Models\ModuleLevel;
use Validator;
use App\Models\Tenant\Skin;

class ConfigurationController extends Controller
{
    public function create()
    {
        return view('tenant.configurations.form');
    }

    public function generateDispatch(Request $request)
    {
        $template = new Template();
        $pdf = new Mpdf();
        $pdf_margin_top = 15;
        $pdf_margin_bottom = 15;
        // $pdf_margin_top = 15;
        $pdf_margin_right = 15;
        // $pdf_margin_bottom = 15;
        $pdf_margin_left = 15;

        $pdf_font_regular = config('tenant.pdf_name_regular');
        $pdf_font_bold = config('tenant.pdf_name_bold');

        if ($pdf_font_regular != false) {
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $pdf = new Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                                             DIRECTORY_SEPARATOR . 'pdf' .
                                             DIRECTORY_SEPARATOR . $base_pdf_template .
                                             DIRECTORY_SEPARATOR . 'font')
                ]),
                'fontdata' => $fontData + [
                    'custom_bold' => [
                        'R' => $pdf_font_bold . '.ttf',
                    ],
                    'custom_regular' => [
                        'R' => $pdf_font_regular . '.ttf',
                    ],
                ],
                'margin_top'    => $pdf_margin_top,
                'margin_right'  => $pdf_margin_right,
                'margin_bottom' => $pdf_margin_bottom,
                'margin_left'   => $pdf_margin_left,
            ]);
        } else {
            $pdf = new Mpdf([
                'margin_top'    => $pdf_margin_top,
                'margin_right'  => $pdf_margin_right,
                'margin_bottom' => $pdf_margin_bottom,
                'margin_left'   => $pdf_margin_left
            ]);
        }
        $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                                             DIRECTORY_SEPARATOR . 'preprinted_pdf' .
                                             DIRECTORY_SEPARATOR . $request->base_pdf_template .
                                             DIRECTORY_SEPARATOR . 'style.css');

        $stylesheet = file_get_contents($path_css);

        // $actions = array_key_exists('actions', $request->inputs)?$request->inputs['actions']:[];
        $actions = [];
        $html = $template->preprintedpdf($request->base_pdf_template, 'dispatch', Company::active(), 'a4');
        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        Storage::put('preprintedpdf' . DIRECTORY_SEPARATOR . $request->base_pdf_template . '.pdf', $pdf->output('', 'S'));

        return $request->base_pdf_template;
    }

    public function show($template)
    {
        return response()->file(storage_path('app' . DIRECTORY_SEPARATOR . 'preprintedpdf' . DIRECTORY_SEPARATOR . $template . '.pdf'));
    }

    // public function dispatch(Request $request) {
    //     dd($request);
    //     return 'prueba';

    //     $fact = DB::connection('tenant')->transaction(function () use($request) {
    //         $facturalo = new Facturalo();
    //         $facturalo->save($request->all());
    //         $facturalo->createXmlUnsigned();
    //         $facturalo->signXmlUnsigned();
    //         $facturalo->createPdf();
    //         $facturalo->senderXmlSignedBill();

    //         return $facturalo;
    //     });

    //     $document = $fact->getDocument();
    //     $response = $fact->getResponse();

    //     return [
    //         'success' => true,
    //         'message' => "Se creo la guía de remisión {$document->series}-{$document->number}",
    //         'data' => [
    //             'id' => $document->id,
    //         ],
    //     ];
    // }

    public function addSeeder()
    {
        $reiniciar = DB::connection('tenant')
                        ->table('format_templates')
                        ->truncate();
        $archivos = Storage::disk('core')->allDirectories('Templates/pdf');
        $collection = [];
        $valor = [];
        foreach ($archivos as $valor) {
            $line = explode('/', $valor);
            if (count($line) <= 3) {
                array_push($collection, $line);
            }
        }

        foreach ($collection as $insertar) {
            $urls = [
                'guide' => \File::exists(public_path('templates/pdf/'.$insertar[2].'/image_guide.png')) ? 'templates/pdf/'.$insertar[2].'/image_guide.png' : '',
                'invoice' => \File::exists(public_path('templates/pdf/'.$insertar[2].'/image.png')) ? 'templates/pdf/'.$insertar[2].'/image.png' : 'templates/pdf/default/image.png',
                'ticket' => \File::exists(public_path('templates/pdf/'.$insertar[2].'/ticket.png')) ? 'templates/pdf/'.$insertar[2].'/ticket.png' : '',
            ];

            $insertar = DB::connection('tenant')
            ->table('format_templates')
            ->insert([
                [
                    'formats' => $insertar[2],
                    'urls' => json_encode($urls),
                    'is_custom_ticket' => \File::exists(public_path('templates/pdf/'.$insertar[2].'/ticket.png')) ? 1 : 0 ]
            ]);
        }

        // revisión custom //obsoleto
        // $exists = Storage::disk('core')->exists('Templates/pdf/custom/style.css');
        // if (!$exists) {
        //     Storage::disk('core')->copy('Templates/pdf/default/style.css', 'Templates/pdf/custom/style.css');
        //     Storage::disk('core')->copy('Templates/pdf/default/invoice_a4.blade.php', 'Templates/pdf/custom/invoice_a4.blade.php');
        //     Storage::disk('core')->copy('Templates/pdf/default/partials/footer.blade.php', 'Templates/pdf/custom/partials/footer.blade.php');
        // }

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function refreshTickets()
    {
        $lists = FormatTemplate::where('is_custom_ticket', true)->get();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function getTicketFormats()
    {
        $formats = FormatTemplate::where('is_custom_ticket', true)->get()->transform(function($row) {
                return $row->getCollectionData();
        });

        return compact('formats');
    }

    public function addPreprintedSeeder()
    {
        $reiniciar = DB::connection('tenant')
                        ->table('preprinted_format_templates')
                        ->truncate();
        $archivos = Storage::disk('core')->allDirectories('Templates/preprinted_pdf');
        $colection = [];
        $valor = [];
        foreach ($archivos as $valor) {
            $lina = explode('/', $valor);
            if (count($lina) <= 3) {
                array_push($colection, $lina);
            }
        }

        foreach ($colection as $insertar) {
            $insertar = DB::connection('tenant')
            ->table('preprinted_format_templates')
            ->insert(['formats' => $insertar[2]]);
        }

        // revisión custom
        $exists = Storage::disk('core')->exists('Templates/preprinted_pdf/custom/style.css');
        if (!$exists) {
            Storage::disk('core')->copy('Templates/preprinted_pdf/default/style.css', 'Templates/preprinted_pdf/custom/style.css');
            Storage::disk('core')->copy('Templates/preprinted_pdf/default/invoice_a4.blade.php', 'Templates/preprinted_pdf/custom/invoice_a4.blade.php');
            Storage::disk('core')->copy('Templates/preprinted_pdf/default/partials/footer.blade.php', 'Templates/preprinted_pdf/custom/partials/footer.blade.php');
        }

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function changeFormat(Request $request)
    {
        $establishment = Establishment::find($request->establishment);
        $establishment->template_pdf = $request->formats;
        $establishment->save();

        // $config_format = config(['tenant.pdf_template' => $format->formats]);
        // $fp = fopen(base_path() .'/config/tenant.php' , 'w');
        // fwrite($fp, '<?php return ' . var_export(config('tenant'), true) . ';');
        // fclose($fp);
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function changeTicketFormat(Request $request)
    {
        $establishment = Establishment::find($request->establishment);
        $establishment->template_ticket_pdf = $request->formats;
        $establishment->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function getFormats()
    {
        $formats = FormatTemplate::get()->transform(function($row) {
                return $row->getCollectionData();
        });

        return compact('formats');

        return $formats;
    }

    public function getPreprintedFormats()
    {
        $formats = DB::connection('tenant')->table('preprinted_format_templates')->get();

        return $formats;
    }

    public function pdfTemplates()
    {
        $establishments = Establishment::select(['id','description','template_pdf'])->get();
        return view('tenant.advanced.pdf_templates')->with('establishments', $establishments);
    }

    public function pdfTicketTemplates()
    {
        $establishments = Establishment::select(['id','description','template_ticket_pdf'])->get();
        return view('tenant.advanced.pdf_ticket_templates')->with('establishments', $establishments);
    }

    public function pdfGuideTemplates()
    {
        return view('tenant.advanced.pdf_guide_templates');
    }

    public function pdfPreprintedTemplates()
    {
        return view('tenant.advanced.pdf_preprinted_templates');
    }

    public function record()
    {
        $configuration = Configuration::first();
        return ['data'=>$configuration->getCollectionData()];
        $record = new ConfigurationResource($configuration);

        return  $record;
    }

    public function store(ConfigurationRequest $request)
    {
        $id = $request->input('id');
        $configuration = Configuration::find($id);
        $configuration->fill($request->all());
        $configuration->save();


        return [
            'success' => true,
            'configuration' => $configuration->getCollectionData(),
            'message' => 'Configuración actualizada',
        ];
    }

    /**
     * Solo guarda lo sdatos de token para el cliente
     *
     * @param Request $request
     *
     * @return array
     */
    public function storeApiRuc( Request  $request)
    {
        $configuration = Configuration::first();
        if(empty($configuration)){
            $configuration = new Configuration();
        }
        $configuration->token_apiruc = $request->token_apiruc;
        $configuration->url_apiruc = $request->url_apiruc;

        $configuration->save();

        return [
            'success' => true,
            'configuration' => $configuration->getCollectionData(),
            'message' => 'Configuración actualizada',
        ];
    }

    public function icbper(Request $request)
    {
        DB::connection('tenant')->transaction(function () use ($request) {
            $id = $request->input('id');
            $configuration = Configuration::find($id);
            $configuration->amount_plastic_bag_taxes = $request->amount_plastic_bag_taxes;
            $configuration->save();

            $items = Item::get(['id', 'amount_plastic_bag_taxes']);

            foreach ($items as $item) {
                $item->amount_plastic_bag_taxes = $configuration->amount_plastic_bag_taxes;
                $item->update();
            }
        });

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function tables()
    {
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $global_discount_types = ChargeDiscountType::whereIn('id', ['02', '03'])->whereActive()->get();

        return compact('affectation_igv_types', 'global_discount_types');
    }

    public function visualDefaults()
    {
        $defaults = [
            'bg'       => 'light',
            'header'   => 'light',
            'sidebars' => 'light',
        ];
        $configuration = Configuration::first();
        $configuration->visual = $defaults;
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function visualSettings(Request $request)
    {
        $visuals = [
            'bg'       => $request->bg,
            'header'   => $request->header,
            'sidebars' => $request->sidebars,
            'navbar' => $request->navbar,
            'sidebar_theme' => $request->sidebar_theme
        ];

        $configuration = Configuration::find(1);
        $configuration->visual = $visuals;
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function getSystemPhone()
    {
        // $configuration = Configuration::first();
        // $ws = $configuration->enable_whatsapp;

        // $current = url('/phone');
        // $parse_current = parse_url($current);
        // $explode_current = explode('.', $parse_current['host']);
        // $app_url = config('app.url');
        // if(!array_key_exists('port', $parse_current)){
        //     $path = $app_url.$parse_current['path'];
        // }else{
        //     $path = $app_url.':'.$parse_current['port'].$parse_current['path'];
        // }

        // $http = new Client(['verify' => false]);
        // $response = $http->request('GET', $path);
        // if($response->getStatusCode() == '200'){
        //     $body = $response->getBody();

        //     $configuration->phone_whatsapp = $body;
        //     $configuration->save();
        // }
        // return 'error';
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $configuration = Configuration::first();

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = date('Ymd') . '_' . $configuration->id . '.' . $ext;

            request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            $file->storeAs('public/uploads/header_images', $name);

            $configuration->header_image = $name;

            $configuration->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name'    => $name,
            ];
        }

        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }

    public function changeMode()
    {
        $configuration = Configuration::first();
        $visual = $configuration->visual;
        $visual->sidebar_theme = $visual->bg === 'dark' ? 'white' : 'dark';
        $visual->bg = $visual->bg === 'dark' ? 'white' : 'dark';
        $configuration->visual = $visual;
        $configuration->save();

        return redirect()->back();
    }


    public function apiruc()
    {
        $configuration = Configuration::first();
        return [
            'url_apiruc' => $configuration->url_apiruc,
            'token_apiruc' => $configuration->token_apiruc,
            'token_false' => !$configuration->UseCustomApiPeruToken(),
        ];
    }

    private function getMenu() {
        $configuration = Configuration::first();
        return $menus = [
            'top_menu_a' => $configuration->top_menu_a_id ? $configuration->top_menu_a : '',
            'top_menu_b' => $configuration->top_menu_b_id ? $configuration->top_menu_b : '',
            'top_menu_c' => $configuration->top_menu_c_id ? $configuration->top_menu_c : '',
            'top_menu_d' => $configuration->top_menu_d_id ? $configuration->top_menu_d : '',
        ];
    }

    public function visualGetMenu()
    {
        $modules = ModuleLevel::where([['route_name', '!=', null],['label_menu', '!=', null]])->get();

        return [
            'modules' => $modules,
            'menu' => $this->getMenu()
        ];
    }

    public function visualSetMenu(Request $request)
    {
        $configuration = Configuration::first();
        $configuration->top_menu_a_id = $request->menu_a;
        $configuration->top_menu_b_id = $request->menu_b;
        $configuration->top_menu_c_id = $request->menu_c;
        $configuration->top_menu_d_id = $request->menu_d;
        $configuration->save();

        return [
            'success' => true,
            'menu' => $this->getMenu(),
            'message' => 'Configuración actualizada',
        ];
    }

    public function visualUploadSkin(Request $request)
    {
        if ($request->file->getClientMimeType() != 'text/css') {
            return [
                'success' => false,
                'message' =>  'Tipo de archivo no permitido',
            ];
        }
        if (Storage::disk('public')->exists('skins'.DIRECTORY_SEPARATOR.$request->file->getClientOriginalName())) {
            return [
                'success' => false,
                'message' =>  'Archivo ya existe',
            ];
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file_content = file_get_contents($file->getRealPath());
            $filename = $file->getClientOriginalName();
            $name = pathinfo($file->getClientOriginalName());


            Storage::disk('public')->put('skins'.DIRECTORY_SEPARATOR.$filename, $file_content);

            $skin = new Skin;
            $skin->filename = $filename;
            $skin->name = $name['filename'];
            $skin->save();

            $skins = Skin::all();
            return [
                'success' => true,
                'message' =>  'Archivo cargado exitosamente',
                'skins' => $skins
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    public function visualDeleteSkin(Request $request)
    {
        $config = Configuration::first();
        if($config->skin_id == $request->id) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar el Tema actual'
            ];
        }


        $skin = Skin::find($request->id);
        Storage::disk('public')->delete('skins'.DIRECTORY_SEPARATOR.$skin->filename);
        $skin->delete();

        $skins = Skin::all();

        return [
            'success' => true,
            'message' =>  'Tema eliminado correctamente',
            'skins' => $skins
        ];
    }
}
