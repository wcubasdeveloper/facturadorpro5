<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 *
 * @package App\Http\Resources\Tenant
 * @mixin JsonResource
 */
class PaymentLinkResource extends JsonResource
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
        return  $this->getRowResourceWithoutPayment();
    }

}
