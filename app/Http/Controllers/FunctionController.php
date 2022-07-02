<?php


    namespace App\Http\Controllers;


    /**
     * Class FunctionController
     *
     * @package App\Http\Controllers
     */
    class FunctionController extends Controller {

        /**
         * Devuelve el valor del indice del array si existe, sino, devuelve un valor por defecto
         *
         * @param array  $array
         * @param string $index
         * @param null   $value
         *
         * @return mixed|null
         */
        public static function InArray($array = [], $index = '', $value = null) {
            if (isset($array[$index])) {
                return $array[$index];
            }
            return $value;
        }
    }
