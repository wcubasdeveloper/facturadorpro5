<?php

namespace App\CoreFacturalo\Requests\Api\Transform\Common;

use App\CoreFacturalo\Requests\Api\Transform\Functions;

class OperationDataTransform
{
    public static function transform($inputs)
    {
        return [
            'country_id' => Functions::valueKeyInArray($inputs, 'codigo_pais'),
            'district_id' => Functions::valueKeyInArray($inputs, 'ubigeo'),
            'address' => Functions::valueKeyInArray($inputs, 'direccion'),
            'address_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_direccion'),
        ];
    }
}