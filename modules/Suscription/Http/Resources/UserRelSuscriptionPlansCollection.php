<?php

    namespace Modules\Suscription\Http\Resources;

    use Illuminate\Http\Resources\Json\ResourceCollection;

    /**
     *
     * @mixin ResourceCollection
     */
    class UserRelSuscriptionPlansCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return mixed
         */
        public function toArray($request)
        {
            return $this->collection->transform(function ($row, $key) {

                /** @var \Modules\Suscription\Models\Tenant\UserRelSuscriptionPlan $row */
                return $row->getCollectionData();

            });
        }
    }
