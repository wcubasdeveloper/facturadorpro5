<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
    use Barryvdh\DomPDF\Facade as PDF;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Modules\DocumentaryProcedure\Exports\StatisticTramiteExport;
    use Modules\DocumentaryProcedure\Models\DocumentaryFile;

    /**
     * Class DocumentarystatisticController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryStatisticController extends Controller
    {
        public function index()
        {
            return view('documentaryprocedure::statistic');
        }

        public function export(Request $request, $type = null)
        {
            $type = 'pdf'; // en excel, no cuadra las celdas
            $records = $this->records($request);
            $filename = 'Reporte_Tramite_' . date('YmdHis');

            if ($type == 'pdf') {


                /** @var \Barryvdh\DomPDF\PDF $pdf */
                $pdf = PDF::loadView('documentaryprocedure::exports.report_statistic',
                    compact('records'))
                    ->setPaper('a4', 'landscape');

                return $pdf->stream($filename . '.pdf');
            }

            $excel = new StatisticTramiteExport();
            $excel->setRecords($records);
            // return $excel->view();
            return $excel->download($filename . '.xlsx');

        }

        public function records(Request $request)
        {
            $request = $request->all();
            $request['date_start'] = $request['date_start'] ?? null;
            $request['date_end'] = $request['date_end'] ?? null;
            $date_start = null;
            $date_end = null;
            FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
            $statistic = [];
            $data = DocumentaryFile::whereBetween('created_at', [$date_start, $date_end])
                ->get()
                ->transform(function (DocumentaryFile $row) use (&$statistic) {
                    $m = $row->created_at->format('m');
                    $y = $row->created_at->format('Y');

                    if (!isset($statistic["$y-$m"])) {
                        $statistic["$y-$m"] = [
                            'complete' => 0,
                            'archive' => 0,
                            'process' => 0,
                            'total' => 0,
                            'month' => self::getMonthString($m)
                        ];
                    }
                    if ($row->is_completed) {
                        ++$statistic["$y-$m"]['complete'];
                    } elseif ($row->is_archive) {
                        ++$statistic["$y-$m"]['archive'];
                    } else {
                        ++$statistic["$y-$m"]['process'];
                    }
                    ++$statistic["$y-$m"]['total'];
                    return $row->getCollectionData();
                });

            return [
                'items' => $data,
                'statistic' => $statistic,
                'total' => $data->count(),
            ];
        }

        protected static function getMonthString($month = 1)
        {
            $months = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Setiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];
            $month = (int)($month);
            return $months[$month] ?? "-";
        }

    }
