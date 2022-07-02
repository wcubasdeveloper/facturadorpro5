<?php

    namespace Modules\Suscription\Http\Resources;


    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * Class ItemResource
     *
     * @package      Modules\Suscription\Http\Resources;
     * @mixin JsonResource
     */
    class UserRelSuscriptionPlansResource extends JsonResource
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
            return $this->getCollectionData(true);

        }
    }
