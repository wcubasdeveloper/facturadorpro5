<?php

namespace Modules\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ValuedKardexFormatSunatExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function records($records) {
        $this->records = $records;
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
    
    public function additionalData($additionalData) {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function view(): View {
        
        return view('inventory::reports.valued_kardex.report_excel_sunat', [
            'records'=> $this->records,
            'company' => $this->company,
            'establishment' => $this->establishment,
            'additionalData' => $this->additionalData
        ]);

    }

}
