<?php

    namespace App\Http\Controllers;

    use Mpdf\Mpdf;
    use Mpdf\MpdfException;
    use setasign\Fpdi\Fpdi;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;

    class PdfUnionController extends Controller
    {
        /**
         * Unifica varios pdf en uno solo.
         *
         * @param Fpdi $fpdi
         * @param Mpdf $pdf
         *
         * @throws MpdfException
         */
        public static function addFpi(Fpdi &$fpdi, Mpdf &$pdf)
        {
            $path = storage_path('app/public/temp_pdf/' . microtime() . ".pdf");
            if (!is_dir(storage_path('app/public/temp_pdf/'))) mkdir(storage_path('app/public/temp_pdf/'));
            ob_clean();
            $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
            $pdf->output($path, 'F');
            if (is_file($path)) {
                $pageCount = $fpdi->setSourceFile($path);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $pageId = $fpdi->ImportPage($pageNo);
                    $s = $fpdi->getTemplatesize($pageId);
                    $fpdi->AddPage($s['orientation'], $s);
                    $fpdi->useImportedPage($pageId);
                }
                unlink($path);
            }

        }

        /**
         * @param Fpdi        $pdf
         * @param string|null $file_name
         *
         * @return BinaryFileResponse
         */
        public static function ResponseAsFile(Fpdi $pdf, ?string $file_name = 'pdf')
        {

            $temp = tempnam(sys_get_temp_dir(), $file_name);
            file_put_contents($temp, $pdf->Output('S'));

            return response()->file($temp);
        }

        //
    }
