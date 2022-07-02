<?php

    namespace App\CoreFacturalo\Requests\Inputs\Common;

    use App\Models\Tenant\Bank;


    /**
     * Class BankInput
     *
     * @package App\CoreFacturalo\Requests\Inputs\Common
     */
    class BankInput
    {
        /**
         * @param int $bank_id
         *
         * @return array|null
         */
        public static function set(?int $bank_id = 0): ?array
        {
            $bank = Bank::find($bank_id);

            if (!$bank) {
                return null;
            }
            return [
                'id' => $bank->id,
                'description' => $bank->description,
                'active' => $bank->active,
            ];


        }
    }
