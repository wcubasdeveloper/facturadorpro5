<?php

    namespace Modules\Report\Http\Resources;

    use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\DocumentItem;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\SaleNoteItem;
    use App\Models\Tenant\User;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Support\Collection;
    use Modules\Report\Helpers\UserCommissionHelper;


    class ReportCommissionCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return Collection
         */
        public function toArray($request)
        {
            /**
             * @var Collection $data
             */

            $data = $this->collection->transform(function ($row, $key) use ($request) {
               
                return UserCommissionHelper::getDataForReportCommission($row, $request);

                // return $data_commission;
                // dd($data_commission);

            });

            return $data;
        }

    }
