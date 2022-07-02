<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashPaymentExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function data($data) {
        $this->data = $data;
        
        return $this;
    }
    
    public function view(): View {
        return view('tenant.cash.report_cash_excel', [
            'data'=> $this->data
        ]);
    }
}
