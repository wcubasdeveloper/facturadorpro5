<?php

namespace Modules\Finance\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class MovementExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return substr('Mov. de ingresos y egresos', 0, 30);
    }

    public function records($records)
    {
        $this->records = $records;

        return $this;
    }

    public function company($company)
    {
        $this->company = $company;

        return $this;
    }

    public function establishment($establishment) {
        $this->establishment = $establishment;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNewFormat(){
        if(!property_exists($this,'newFormat')){
            $this->newFormat = false;
        }
        return (bool) $this->newFormat;
    }

    /**
     * @param bool $newFormat
     *
     * @return MovementExport
     */
    public function setNewFormat(bool $newFormat)
    : MovementExport {
        $this->newFormat = $newFormat;
        return $this;
    }

    public function view(): View {
        if($this->isNewFormat()){
            return view('finance::movements.new_report_excel', [
                'records'=> $this->records,
                'company' => $this->company,
                'establishment'=>$this->establishment
            ]);
        }
        return view('finance::movements.report_excel', [
            'records'=> $this->records,
            'company' => $this->company,
            'establishment'=>$this->establishment
        ]);
    }
}
