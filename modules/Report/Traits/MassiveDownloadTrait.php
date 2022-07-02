<?php

    namespace Modules\Report\Traits;

    use App\CoreFacturalo\Template;
    use App\Http\Controllers\PdfUnionController;
    use App\Models\Tenant\{BankAccount, Dispatch, Document, Establishment, SaleNote};
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use Carbon\Carbon;
    use ErrorException;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Str;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;
    use Mpdf\HTMLParserMode;
    use Mpdf\Mpdf;
    use Mpdf\MpdfException;
    use PhpParser\Node\Expr\Cast\Object_;
    use Response;
    use setasign\Fpdi\Fpdi;
    use ZipArchive;


    trait MassiveDownloadTrait
    {

        public function getData($document_types, $params)
        {

            $documents_01 = [];
            $documents_03 = [];
            $sale_notes = [];
            $dispatches = [];

            foreach ($document_types as $document_type) {

                switch ($document_type) {
                    case '01':
                        $documents_01 = $this->getRecordsByModel(Document::class, $params)->where('document_type_id', '01')->get();
                        break;
                    case '03':
                        $documents_03 = $this->getRecordsByModel(Document::class, $params)->where('document_type_id', '03')->get();
                        break;
                    case '80':
                        $sale_notes = $this->getRecordsByModel(SaleNote::class, $params)->get();
                        break;
                    case '09':
                        $dispatches = $this->getRecordsByModel(Dispatch::class, $params)->get();
                        break;
                    default:
                        $documents_01 = $this->getRecordsByModel(Document::class, $params)->whereIn('document_type_id', ['01', '03'])->get();
                        $sale_notes = $this->getRecordsByModel(SaleNote::class, $params)->get();
                        $dispatches = $this->getRecordsByModel(Dispatch::class, $params)->get();
                        break;
                }

            }

            return [
                'documents_01' => $documents_01,
                'documents_03' => $documents_03,
                'sale_notes' => $sale_notes,
                'dispatches' => $dispatches,
            ];

        }

        public function getRecordsByModel($model, $params)
        {
            $param_array = (array)$params;
            /** @var Builder $records */
            $records = $model::whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                ->latest()
                ->whereTypeUser();

            if ($params->person_id) {
                $records = $records->where('customer_id', $params->person_id);
            }
            if ($model == Document::class || $model == SaleNote::class) {
                if ($params->series) {
                    $records = $records->where('series', $params->series);
                }
                if ($params->sellers && $model == Document::class) {
                    $records = $records->wherein('seller_id', $params->sellers);
                }
            }

            if (isset($param_array['min']) && isset($param_array['max'])) {
                $min = (int)$param_array['min'];
                $max = (int)$param_array['max'];
                if($max < $min){
                    $min = (int)$param_array['max'];
                    $max = (int)$param_array['min'];
                }
                $records->whereBetween('number', [$min, $max]);
            }
            return $records;
        }

        public function getTotals($document_types, $params)
        {

            $total_documents = 0;

            foreach ($document_types as $document_type) {

                switch ($document_type) {
                    case '01':
                        $total_documents += $this->getRecordsByModel(Document::class, $params)->where('document_type_id', '01')->count();
                        break;
                    case '03':
                        $total_documents += $this->getRecordsByModel(Document::class, $params)->where('document_type_id', '03')->count();
                        break;
                    case '80':
                        $total_documents += $this->getRecordsByModel(SaleNote::class, $params)->count();
                        break;
                    case '09':
                        $total_documents += $this->getRecordsByModel(Dispatch::class, $params)->count();
                        break;
                    default:
                        $total_documents += $this->getRecordsByModel(Document::class, $params)->whereIn('document_type_id', ['01', '03'])->count();
                        $total_documents += $this->getRecordsByModel(SaleNote::class, $params)->count();
                        $total_documents += $this->getRecordsByModel(Dispatch::class, $params)->count();
                        break;
                }

            }

            return $total_documents;

        }

        public function toPrintByView($folder, $view)
        {

            $temp = tempnam(sys_get_temp_dir(), $folder);
            file_put_contents($temp, $view);

            return response()->file($temp);

        }


        public function newCreatePdf($sale_note = null, $format_pdf = 'a4', $filename = null)
        {

            ini_set("pcre.backtrack_limit", "5000000");
            $template = new Template();
            $pdf = new Mpdf();

            $this->company = ($this->company != null) ? $this->company : Company::active();
            $this->document = ($sale_note != null) ? $sale_note : $this->sale_note;

            $this->configuration = Configuration::first();
            // $configuration = $this->configuration->formats;
            $base_template = Establishment::find($this->document->establishment_id)->template_pdf;

            $html = $template->pdf($base_template, "sale_note", $this->company, $this->document, $format_pdf);

            if (($format_pdf === 'ticket') or ($format_pdf === 'ticket_58')) {

                $width = ($format_pdf === 'ticket_58') ? 56 : 78;
                if (config('tenant.enabled_template_ticket_80')) $width = 76;

                $company_logo = ($this->company->logo) ? 40 : 0;
                $company_name = (strlen($this->company->name) / 20) * 10;
                $company_address = (strlen($this->document->establishment->address) / 30) * 10;
                $company_number = $this->document->establishment->telephone != '' ? '10' : '0';
                $customer_name = strlen($this->document->customer->name) > '25' ? '10' : '0';
                $customer_address = (strlen($this->document->customer->address) / 200) * 10;
                $p_order = $this->document->purchase_order != '' ? '10' : '0';

                $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
                $total_free = $this->document->total_free != '' ? '10' : '0';
                $total_unaffected = $this->document->total_unaffected != '' ? '10' : '0';
                $total_exonerated = $this->document->total_exonerated != '' ? '10' : '0';
                $total_taxed = $this->document->total_taxed != '' ? '10' : '0';
                $quantity_rows = count($this->document->items);
                $payments = $this->document->payments()->count() * 2;

                $extra_by_item_description = 0;
                $discount_global = 0;
                foreach ($this->document->items as $it) {
                    if (strlen($it->item->description) > 100) {
                        $extra_by_item_description += 24;
                    }
                    if ($it->discounts) {
                        $discount_global = $discount_global + 1;
                    }
                }
                $legends = $this->document->legends != '' ? '10' : '0';
                $bank_accounts = BankAccount::count() * 6;

                $pdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => [
                        $width,
                        60 +
                        (($quantity_rows * 8) + $extra_by_item_description) +
                        ($discount_global * 3) +
                        $company_logo +
                        $payments +
                        $company_name +
                        $company_address +
                        $company_number +
                        $customer_name +
                        $customer_address +
                        $p_order +
                        $legends +
                        $bank_accounts +
                        $total_exportation +
                        $total_free +
                        $total_unaffected +
                        $total_exonerated +
                        $total_taxed],
                    'margin_top' => 0,
                    'margin_right' => 2,
                    'margin_bottom' => 0,
                    'margin_left' => 2
                ]);
            } elseif ($format_pdf === 'a5') {

                $company_name = (strlen($this->company->name) / 20) * 10;
                $company_address = (strlen($this->document->establishment->address) / 30) * 10;
                $company_number = $this->document->establishment->telephone != '' ? '10' : '0';
                $customer_name = strlen($this->document->customer->name) > '25' ? '10' : '0';
                $customer_address = (strlen($this->document->customer->address) / 200) * 10;
                $p_order = $this->document->purchase_order != '' ? '10' : '0';

                $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
                $total_free = $this->document->total_free != '' ? '10' : '0';
                $total_unaffected = $this->document->total_unaffected != '' ? '10' : '0';
                $total_exonerated = $this->document->total_exonerated != '' ? '10' : '0';
                $total_taxed = $this->document->total_taxed != '' ? '10' : '0';
                $quantity_rows = count($this->document->items);
                $discount_global = 0;
                foreach ($this->document->items as $it) {
                    if ($it->discounts) {
                        $discount_global = $discount_global + 1;
                    }
                }
                $legends = $this->document->legends != '' ? '10' : '0';


                $alto = ($quantity_rows * 8) +
                    ($discount_global * 3) +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed;
                $diferencia = 148 - (float)$alto;

                $pdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => [
                        210,
                        $diferencia + $alto
                    ],
                    'margin_top' => 2,
                    'margin_right' => 5,
                    'margin_bottom' => 0,
                    'margin_left' => 5
                ]);


            } else {

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
                    ]);
                }

            }

            $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                DIRECTORY_SEPARATOR . 'pdf' .
                DIRECTORY_SEPARATOR . $base_template .
                DIRECTORY_SEPARATOR . 'style.css');

            $stylesheet = file_get_contents($path_css);

            $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            if (config('tenant.pdf_template_footer')) {
                // if (($format_pdf != 'ticket') AND ($format_pdf != 'ticket_58') AND ($format_pdf != 'ticket_50')) {
                if ($base_template != 'full_height') {
                    $html_footer = $template->pdfFooter($base_template, $this->document);
                } else {
                    $html_footer = $template->pdfFooter('default', $this->document);
                }
                $html_footer_legend = "";
                if ($base_template != 'legend_amazonia') {
                    if ($this->configuration->legend_footer) {
                        $html_footer_legend = $template->pdfFooterLegend($base_template, $this->document);
                    }
                }
                $pdf->SetHTMLFooter($html_footer . $html_footer_legend);
                // }
            }

            if ($base_template === 'brand') {

                if (($format_pdf === 'ticket') || ($format_pdf === 'ticket_58') || ($format_pdf === 'ticket_50')) {
                    $pdf->SetHTMLHeader("");
                    $pdf->SetHTMLFooter("");
                }
            }

            $this->uploadFile($this->document->filename, $pdf->output('', 'S'), 'sale_note');
        }

        public function createPdf($data,
                                  $format_pdf = 'a4',
                                  $array = [])
        {

            ini_set("pcre.backtrack_limit", "5000000");

            $company = Company::active();
            $configuration = Configuration::first();
            $template = new Template();
             // $base_pdf_template = $configuration->formats;
            $base_pdf_template = \Auth::user()->establishment->template_pdf;
            $pdf_margin_top = 15;
            $pdf_margin_right = 15;
            $pdf_margin_bottom = 15;
            $pdf_margin_left = 15;

            if ($base_pdf_template === 'full_height') {
                $pdf_margin_top = 5;
                $pdf_margin_right = 5;
                $pdf_margin_bottom = 5;
                $pdf_margin_left = 5;
            }

            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');
            $path_css = app_path('CoreFacturalo' .
                DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . $base_pdf_template . DIRECTORY_SEPARATOR . 'style.css');
            $stylesheet = file_get_contents($path_css);


            if (($format_pdf === 'ticket') or ($format_pdf === 'ticket_58')) {
                $base_pdf_template = $configuration->formats;
                $width = ($format_pdf === 'ticket_58') ? 56 : 78;

                if (config('tenant.enabled_template_ticket_80')) $width = 76;
                $pdf_margin_top = 5;
                $pdf_margin_right = 1;
                $pdf_margin_bottom = 5;
                $pdf_margin_left = 1;

                $pdf_data = [

                    'margin_top' => $pdf_margin_top,
                    'margin_right' => $pdf_margin_right,
                    'margin_bottom' => $pdf_margin_bottom,
                    'margin_left' => $pdf_margin_left
                ];


                $documents = isset($data['documents_01']) ? $data['documents_01'] : [];
                $type = 'invoice';
                $pdf_array = [];
                $pdfj = new Fpdi();
                foreach ($documents as $document) {
                    /** @var Document $document */
                    $pdf = $this->addRecordToPdf(
                        $document,
                        $format_pdf,
                        $base_pdf_template,
                        $type,
                        $stylesheet,
                        $pdf_data
                    );

                    $pdf1 = $pdf;
                    PdfUnionController::addFpi($pdfj, $pdf1);
                    // $this->SaveTempPdf($pdf,$pdf_array,$document);
                }

                $documents = isset($data['documents_03']) ? $data['documents_03'] : [];
                $type = 'invoice';

                foreach ($documents as $document) {
                    /** @var Document $document */
                    $pdf = $this->addRecordToPdf(
                        $document,
                        $format_pdf,
                        $base_pdf_template,
                        $type,
                        $stylesheet,
                        $pdf_data
                    );

                    $pdf1 = $pdf;
                    PdfUnionController::addFpi($pdfj, $pdf1);
                    // $this->SaveTempPdf($pdf,$pdf_array,$document);
                }

                $documents = isset($data['sale_notes']) ? $data['sale_notes'] : [];
                $type = 'sale_note';

                foreach ($documents as $document) {
                    /** @var SaleNote $document */
                    $pdf = $this->addRecordToPdf(
                        $document,
                        $format_pdf,
                        $base_pdf_template,
                        $type,
                        $stylesheet,
                        $pdf_data
                    );

                    $pdf1 = $pdf;
                    PdfUnionController::addFpi($pdfj, $pdf1);
                    //   $this->SaveTempPdf($pdf,$pdf_array,$document);
                }
                /*
                    $documents = isset($data['dispatches']) ? $data['dispatches'] : [];
                    $type = 'dispatch';

                    foreach ($documents as $document) {
                        dd([
                            __LINE__,
                            $document
                        ]);
                        $pdf = $this->addRecordToPdf(
                            $document,
                            $format_pdf,
                            $base_pdf_template,
                            $type,
                            $stylesheet,
                            $pdf_data
                        );
                        $pdf1 = $pdf;
                        PdfUnionController::addFpi($pdfj, $pdf1);
                        // $this->SaveTempPdf($pdf,$pdf_array,$document);
                    }
                */
                return $pdfj->Output('S');

            } else {

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
                        'margin_top' => $pdf_margin_top,
                        'margin_right' => $pdf_margin_right,
                        'margin_bottom' => $pdf_margin_bottom,
                        'margin_left' => $pdf_margin_left
                    ]);

                } else {

                    $pdf = new Mpdf([
                        'margin_top' => $pdf_margin_top,
                        'margin_right' => $pdf_margin_right,
                        'margin_bottom' => $pdf_margin_bottom,
                        'margin_left' => $pdf_margin_left
                    ]);

                }


                $pdf = $this->addPageRecords($data['documents_01'], $template, $base_pdf_template, $company, $pdf, $stylesheet, $format_pdf, 'invoice', $configuration);

                $pdf = $this->addPageRecords($data['documents_03'], $template, $base_pdf_template, $company, $pdf, $stylesheet, $format_pdf, 'invoice', $configuration);

                $pdf = $this->addPageRecords($data['dispatches'], $template, $base_pdf_template, $company, $pdf, $stylesheet, $format_pdf, 'dispatch', $configuration);

                $pdf = $this->addPageRecords($data['sale_notes'], $template, $base_pdf_template, $company, $pdf, $stylesheet, $format_pdf, 'sale_note', $configuration);
            }
            /** @var Mpdf $pdf */
            return $pdf->output('', 'S');

        }

        public function addRecordToPdf(
            $document,
            $format_pdf = 'a4',
            $base_pdf_template = '',
            $type = '',
            $stylesheet = '',
            $format = []
        ): Mpdf
        {
            $width = 100;
            if ($format_pdf == 'ticket' || $format_pdf == 80) $width = 78;
            if ($format_pdf == 'ticket_58') $width = 56;
            $configuration = Configuration::first();
            $company = Company::active();
            $template = new Template();

            $format['mode'] = 'utf-8';
            $extra_by_item_description = 0;
            $discount_global = 0;
            $quantity_rows = count($document->items);
            $payments = $document->payments()->count() * 2;
            $company_logo = ($company->logo) ? 40 : 0;
            $company_name = (strlen($company->name) / 20) * 10;
            $company_address = (strlen($document->establishment->address) / 30) * 10;
            $company_number = $document->establishment->telephone != '' ? 10 : 0;
            $customer_name = strlen($document->customer->name) > '25' ? 10 : 0;
            $customer_address = (strlen($document->customer->address) / 200) * 10;
            $p_order = $document->purchase_order != '' ? 10 : 0;

            foreach ($document->items as $it) {
                if (strlen($it->item->description) > 100) {
                    $extra_by_item_description += 24;
                }
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $extra_by_item_description = (($quantity_rows * 8) + 8 + $extra_by_item_description);
            $discount_global *= 3;
            $legends = $document->legends != '' ? 10 : 0;
            $total_exportation = $document->total_exportation != '' ? 10 : 0;
            $total_free = $document->total_free != '' ? 10 : 0;
            $total_unaffected = $document->total_unaffected != '' ? 10 : 0;
            $total_exonerated = $document->total_exonerated != '' ? 10 : 0;
            $total_taxed = $document->total_taxed != '' ? 10 : 0;
            $bank_accounts = BankAccount::count() * 6;

            $base_h = ($type == 'sale_note') ? 60 : 120;
            $height =
                $base_h
                + $company_logo
                + $company_name
                + $bank_accounts
                + $extra_by_item_description
                + $discount_global
                + $payments
                + $company_address
                + $company_number
                + $customer_name
                + $customer_address
                + $p_order
                + $legends
                + $total_exportation
                + $total_free
                + $total_unaffected
                + $total_exonerated
                + $total_taxed;
            $format['format'] = [
                $width,
                $height,
            ];


            $pdf = new Mpdf($format);
            $html = $template->pdf(
                $base_pdf_template,
                $type,
                $company,
                $document,
                $format_pdf);
            $html_footer_legend = "";
            if (config('tenant.pdf_template_footer')) {
                switch ($type) {
                    case 'invoice':
                        $html_footer = $template->pdfFooter($base_pdf_template, $document);
                        if ($configuration->legend_footer) {
                            $html_footer_legend = $template->pdfFooterLegend($base_pdf_template, $document);
                        }
                        $pdf->SetHTMLFooter($html_footer . $html_footer_legend);
                        break;

                    case 'dispatch':
                        $html_footer = $template->pdfFooter($base_pdf_template, $document);
                        $pdf->SetHTMLFooter($html_footer . $html_footer_legend);
                        break;

                    case 'sale_note':
                        $html_footer = ($base_pdf_template != 'full_height') ? $template->pdfFooter($base_pdf_template, $document) : $template->pdfFooter('default', $document);
                        $pdf->SetHTMLFooter($html_footer);
                        break;

                }

            }
            $pdf->AddPage();
            $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
            return $pdf;


        }

        public function addPageRecords($documents, $template, $base_pdf_template, $company, $pdf, $stylesheet, $format_pdf, $type, $configuration)
        {

            foreach ($documents as $document) {

                $html = $template->pdf($base_pdf_template, $type, $company, $document, $format_pdf);
                $html_footer_legend = "";

                if (config('tenant.pdf_template_footer')) {

                    switch ($type) {

                        case 'invoice':
                            $html_footer = $template->pdfFooter($base_pdf_template, $document);
                            if ($configuration->legend_footer) {
                                $html_footer_legend = $template->pdfFooterLegend($base_pdf_template, $document);
                            }
                            $pdf->SetHTMLFooter($html_footer . $html_footer_legend);
                            break;

                        case 'dispatch':
                            $html_footer = $template->pdfFooter($base_pdf_template, $document);
                            $pdf->SetHTMLFooter($html_footer . $html_footer_legend);
                            break;

                        case 'sale_note':
                            $html_footer = ($base_pdf_template != 'full_height') ? $template->pdfFooter($base_pdf_template, $document) : $template->pdfFooter('default', $document);
                            $pdf->SetHTMLFooter($html_footer);
                            break;

                    }

                }

                $pdf = $this->nextPage($pdf, $stylesheet, $html);

            }

            return $pdf;

        }

        public function nextPage($pdf, $stylesheet, $html)
        {

            $pdf->AddPage();
            $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            return $pdf;
        }

        /**
         * Genera un archivo PDF temporal
         *
         * @param Mpdf $pdf
         * @param      $list_files
         * @param      $document
         *
         * @throws MpdfException
         */
        public function SaveTempPdf(Mpdf $pdf, &$list_files, $document)
        {
            /** @var Document $document */
            $name = strtoupper(STR::slug("ticket " . $document->series . " " . $document->number, '-'));
            $path = storage_path('app/public/temp_pdf/' . $name . ".pdf");
            if (!is_dir(storage_path('app/public/'))) mkdir(storage_path('app/public/'));
            if (!is_dir(storage_path('app/public/temp_pdf/'))) mkdir(storage_path('app/public/temp_pdf/'));
            $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

            try {
                unlink($path);
            } catch (ErrorException $e) {

            }

            $pdf->output($path, 'F');
            $list_files[$name] = $path;


        }


    }
