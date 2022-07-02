<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Configuration;
use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\True_;

/**
 * Class DispatchItemCollection
 *
 * @package App\Http\Resources\Tenant
 * @mixin ResourceCollection
 */
class DispatchItemCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function toArray($request)
	{
        $configuration =  Configuration::first();
		return $this->collection->transform(function ($row, $key) use($configuration) {
		    /** @var \App\Models\Tenant\DispatchItem $row */
            return $row->getCollectionData($configuration);

		});
	}
}
