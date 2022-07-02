<?php

    namespace App\Traits;

    use App\Models\Tenant\Document;
    use App\Models\Tenant\Quotation;
    use App\Models\Tenant\SaleNote;
    use Log;
    use Modules\Sale\Models\Contract;
    use Modules\Sale\Models\TechnicalService;

    /**
     *Se encarga de colocar el seller_id cuando no exista.
     */
    trait SellerIdTrait
    {


        /**
         * si seller_id esta vacio, ajusta el seler id al usuario.
         *
         * @param Document|Quotation|SaleNote|TechnicalService|Contract $model
         */
        public static function adjustSellerIdField(&$model): void
        {
            if (empty($model->seller_id)) {
                $model->seller_id = $model->user_id;
            }

        }

    }
