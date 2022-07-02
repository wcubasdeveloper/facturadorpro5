<?php

namespace Modules\DocumentaryProcedure\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Class TramiteExport
 *
 * @package Modules\DocumentaryProcedure\Exports
 */
class TramiteExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /** @var \Illuminate\Database\Eloquent\Collection  */
    protected $records;

    /**
     * @return mixed
     */
    public function getEstablishment() {
        return $this->establishment;
    }

    /**
     * @param mixed $establishment
     *
     * @return TramiteExport
     */
    public function setEstablishment($establishment) {
        $this->establishment = $establishment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @param mixed $company
     *
     * @return TramiteExport
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecords()
    : \Illuminate\Database\Eloquent\Collection {
        return $this->records;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $records
     *
     * @return TramiteExport
     */
    public function setRecords(\Illuminate\Database\Eloquent\Collection $records)
    : TramiteExport {
        $this->records = $records;
        return $this;
    }

    public function view(): View {
        return view('documentaryprocedure::exports.report_excel', [
            'records'=> $this->getRecords(),
            'company' => $this->company,
            'establishment'=>$this->establishment
        ]);
    }
}
