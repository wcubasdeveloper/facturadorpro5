<?php

namespace Modules\Finance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class BalanceExport implements  FromView, ShouldAutoSize
{
    use Exportable;

    public function records($records) {
        $this->records = $records;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data= []) {
        $this->data = $data;

        return $this;
    }


    public function company($company) {
        $this->company = $company;

        return $this;
    }

    public function establishment($establishment) {
        $this->establishment = $establishment;

        return $this;
    }

    public function view(): View {
        return view('finance::balance.report_excel', [
            'records'=> $this->records,
            'data'=> $this->data,
            'company' => $this->company,
            'establishment'=>$this->establishment
        ]);
    }
}
