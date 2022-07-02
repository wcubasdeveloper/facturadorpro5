<?php

namespace Modules\Purchase\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection; 
use Carbon\Carbon;

class PurchaseSeriesImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);

            $news_rows = [];

            foreach ($rows as $row)
            {

                $states = collect(['Activo', 'Inactivo', 'Desactivado', 'Voz', 'M2m']);

                $search_state = $states->first(function($state) use($row){
                    return $state == $row[1];
                });
                // dd($search_state);

                $series = $row[0];
                $state =  $search_state ? $search_state : 'Activo';

                $date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]))->format('Y-m-d');

                $news_rows [] = [
                    'series' => $series,
                    'state' => $state,
                    'date' => $date,
                ];

            }

            $this->data = compact('total', 'news_rows');

    }

    public function getData()
    {
        return $this->data;
    }
}
