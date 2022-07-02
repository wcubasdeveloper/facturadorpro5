<?php

namespace App\Http\Controllers\Tenant\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Quotation;
use App\Http\Resources\Tenant\QuotationCollection;
use App\Http\Controllers\Tenant\QuotationController as QuotationControllerWeb;

class QuotationController extends Controller
{
    public function list()
    {
        $records = Quotation::orderBy('prefix', 'desc')->take(50)->get();
        $records = new QuotationCollection($records); // crear nuevo collection para apis

        return $records;
    }

    public function store(Request $request)
    {
        $request['establishment_id'] = $request['establishment_id'] ? $request['establishment_id'] : auth()->user()->establishment_id;

        DB::connection('tenant')->transaction(function () use ($request) {
            $quotation_web = new QuotationControllerWeb;
            $data = $quotation_web->mergeData($request);
            $data['terms_condition'] = $quotation_web->getTermsCondition();

            $this->quotation =  Quotation::create($data);

            foreach ($data['items'] as $row) {
                $this->quotation->items()->create($row);
            }

            $quotation_web->savePayments($this->quotation, $data['payments']);

            $this->setFilename();
            $quotation_web->createPdf($this->quotation, "a4", $this->quotation->filename);
        });

        return [
            'success' => true,
            'data' => [
                'number_full' => $this->quotation->number_full,
                'external_id' => $this->quotation->external_id,
                'print_a4'    => url('')."/quotations/print/{$this->quotation->external_id}/a4",
            ],
        ];
    }

    private function setFilename(){

        $name = [$this->quotation->prefix,$this->quotation->id,date('Ymd')];
        $this->quotation->filename = join('-', $name);
        $this->quotation->save();

    }
}
