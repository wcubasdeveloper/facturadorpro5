<?php

namespace App\CoreFacturalo\Requests\Api\Transform;

/**
 * Class Functions
 *
 * @package App\CoreFacturalo\Requests\Api\Transform
 */
class Functions
{

    /**
     * @param array  $inputs
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public static function valueKeyInArray($inputs, $key, $default = null) {
        return isset($inputs[$key]) ? $inputs[$key] : $default;
        return array_key_exists($key, $inputs) ? $inputs[$key] : $default;
    }

}
