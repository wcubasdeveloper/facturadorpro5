<?php

    namespace Modules\FullSuscription\Http\Resources;


    use App\Models\Tenant\Person;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    class SuscriptionPersonCollection extends ResourceCollection
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
                /** @var Person $row */
                return $row->getCollectionData(true, false, true);
            });
        }
    }
