<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * Class PurchaseCollection
 *
 * @package App\Http\Resources\Tenant
 */
class PurchaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Collection
     */
    public function toArray($request)
    {

        $apply_conversion_to_pen = $request->apply_conversion_to_pen == 'true';

        return $this->collection->transform(function($row, $key) use($apply_conversion_to_pen){

            $data = $row->getCollectionData();

            // dd($apply_conversion_to_pen, $data);
            $data['description_apply_conversion_to_pen'] = null;

            if($apply_conversion_to_pen && $row->isCurrencyTypeUsd())
            {
                $data['total_exportation'] = $row->getConvertTotalExportationToPen();
                $data['total_free'] = $row->getConvertTotalFreeToPen();
                $data['total_unaffected'] = $row->getConvertTotalUnaffectedToPen();
                $data['total_exonerated'] = $row->getConvertTotalExoneratedToPen();
                $data['total_taxed'] = $row->getConvertTotalTaxedToPen();
                $data['total_igv'] = $row->getConvertTotalIgvToPen();
                $data['total_isc'] = $row->getConvertTotalIscToPen();
                $data['total'] = $row->getConvertTotalToPen();
                $data['description_apply_conversion_to_pen'] = 'Se aplicó conversión a soles';
            }

            return $data;

        });
    }

}
