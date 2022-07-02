<?php

    namespace Modules\Production\Http\Resources;


    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Modules\Production\Models\Production;

    class ProductionCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return array
         */
        public function toArray($request)
        {
            return $this->collection->transform(function (Production $row, $key) {
                return $row->getCollectionData();
            });
        }

    }
