<?php

    namespace Modules\FullSuscription\Http\Resources;


    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * Class ItemResource
     *
     * @package      Modules\FullSuscription\Http\Resources;
     * @mixin JsonResource
     */
    class SuscriptionPlansResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request
         *
         * @return array
         */
        public function toArray($request)
        {
            return $this->getCollectionData();

        }
    }
