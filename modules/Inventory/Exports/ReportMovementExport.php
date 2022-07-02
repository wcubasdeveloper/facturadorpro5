<?php

namespace Modules\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportMovementExport implements  FromView, ShouldAutoSize
{

    use Exportable;

    protected $data;

    public function data($data) {
        $this->data = $data;
        return $this;
    } 

    public function view(): View {

        return view('inventory::reports.movements.report_template', $this->data);
    }

}
