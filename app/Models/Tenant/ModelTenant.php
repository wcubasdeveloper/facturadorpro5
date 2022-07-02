<?php

    namespace App\Models\Tenant;

    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;

    /**
     * Class ModelTenant
     *
     * @package App\Models\Tenant
     * @mixin Model
     * @mixin \Eloquent
     * @mixin \Illuminate\Database\Query\Builder as Builder
     * @mixin \Illuminate\Database\Eloquent\Collection
     * @method static Builder|ModelTenant newModelQuery()
     * @method static Builder|ModelTenant newQuery()
     * @method static Builder|ModelTenant query()
     */
    class ModelTenant extends Model
    {
        use UsesTenantConnection;

        public const RESERVED_SYMBOLS_FILTER = ['-', '+', '<', '>', '@', '(', ')', '~'];

        public const VOIDED_REJECTED_IDS = ['09', '11'];

        /**
         * Devuelve un esqueleto del array de data extra. Previene error de no enconrarse la funcion en otros modelos
         *
         * @return array
         */
        public function getPrintExtraData()
        {

            return [
                'colors' => null,
                'CatItemUnitsPerPackage' => null,
                'CatItemMoldProperty' => null,
                'CatItemProductFamily' => null,
                'CatItemMoldCavity' => null,
                'CatItemPackageMeasurement' => null,
                'CatItemStatus' => null,
                'CatItemUnitBusiness' => null,
                'CatItemSize' => null,
                'image' => null,
                'image_medium' => null,
                'image_small' => null,
            ];
        }

        /**
         * Evalua si el attr se encuentra en el modelo.
         *
         * @param $attr
         *
         * @return bool
         *@example
         *<code>
         *         if($row->hasAttribute('item')) {
         *         // do something
         * }
         * </code>
         */
        public function hasAttribute($attr)
        {
            return array_key_exists($attr, $this->attributes);
        }

                
        /**
         * @param  float $value
         * @param  float $exchange_rate_sale
         * @return float
         */
        public function generalConvertValueToPen($value, $exchange_rate_sale)
        {
            return $value * $exchange_rate_sale;
        }
        

        /**
         * 
         * Aplicar formato a fechas
         *
         * @param  $date
         * @param  string $format
         * @return string
         */
        public function generalFormatDate($date, $format = 'Y-m-d')
        {
            return $date->format($format);
        }
        
        
        /**
         * 
         * Reemplazar simbolos reservados por espacio, para busqueda avanzada
         *
         * @param  string $value
         * @return string
         */
        public function replaceReservedSymbols($value)
        {
            return str_replace(self::RESERVED_SYMBOLS_FILTER, ' ', $value);
        }

        
        /**
         * 
         * Obtener arreglo con los valores a buscar - busqueda avanzada
         *
         * @param  string $value
         * @return array
         */
        public function getSearchValues($value)
        {
            $search_term = $this->replaceReservedSymbols($value);

            return preg_split('/\s+/', $search_term, -1, PREG_SPLIT_NO_EMPTY);
        }

    
        /**
         * 
         * Aplicar formato
         *
         * @param  $value
         * @param  int $decimals
         * @return string
         */
        public function generalApplyNumberFormat($value, $decimals = 2)
        {
            return number_format($value, $decimals, ".", "");
        }
        

    }
