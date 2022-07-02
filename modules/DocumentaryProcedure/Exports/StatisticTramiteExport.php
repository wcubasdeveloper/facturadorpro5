<?php

namespace Modules\DocumentaryProcedure\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Class StadisticTramiteExport
 *
 * @package Modules\DocumentaryProcedure\Exports
 */
class StatisticTramiteExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /** @var array */
    protected $records;

    /**
     * @return array
     */
    public function getRecords(): array
    {
        return $this->records??[];
    }

    /**
     * @param array|null $records
     *
     * @return StatisticTramiteExport
     */
    public function setRecords(?array $records=[]): StatisticTramiteExport
    {
        $this->records = $records;
        return $this;
    }



    public function view(): View {
        return view('documentaryprocedure::exports.report_statistic', [
            'records'=> $this->getRecords(),
        ]);
    }
}
