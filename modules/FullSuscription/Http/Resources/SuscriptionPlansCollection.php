<?php

    namespace Modules\FullSuscription\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Modules\FullSuscription\Models\Tenant\SuscriptionPlan;

    /**
     * @mixin ResourceCollection
     */
    class SuscriptionPlansCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return mixed
         */
        public function toArray($request)
        {
            return $this->collection->transform(function ($row, $key) {

                /** @var SuscriptionPlan $row */
                return $row->getCollectionData();

            });
        }
    }
