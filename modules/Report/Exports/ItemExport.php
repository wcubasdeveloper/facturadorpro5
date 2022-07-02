<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemExport implements  FromView, ShouldAutoSize
{

    use Exportable;
    protected $withExtraData;
    /**
     * @return bool
     */
    public function isWithExtraData(): bool
    {
        return (bool)$this->withExtraData;
    }

    /**
     * @param bool $withExtraData
     *
     * @return ItemExport
     */
    public function setWithExtraData(bool $withExtraData = false): ItemExport
    {
        $this->withExtraData = (bool) $withExtraData;
        return $this;
    }


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

    /**
     * @return string
     */
    public function getType()
    : string {
        return empty($this->type)?'':$this->type;
    }

    /**
     * @param string $type
     *
     * @return ItemExport
     */
    public function setType(string $type)
    : ItemExport {
        $this->type = $type;
        return $this;
    }


    public function view(): View {
        $view = "report_excel";
        if($this->isWithExtraData()){
            $view = "report_excel_extra_data";
        }
        return view('report::items.'.$view, [
            'records'=> $this->records,
            'company' => $this->company,
            'type' => $this->getType(),
            'establishment'=>$this->establishment
        ]);
    }
}
