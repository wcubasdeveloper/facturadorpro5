<?php

namespace Modules\MercadoPago\Traits;

use Modules\MercadoPago\Models\TransactionState;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant\{
    Company
};


trait UtilityTrait
{ 

    public function getErrorMessage($message) {

        return [
            'success' => false,
            'message' => $message
        ];

    }


    public function getTransactionState($status = 'other', $status_detail = 'other'){
        
        $transaction_state = TransactionState::whereStatus($status)->whereStatusDetail($status_detail)->first();
        
        if($transaction_state){
            return $transaction_state;
        }

        return TransactionState::findOrFail('00');

    }



    public function searchPayment($paymentId, $access_token_mp)
    {
 
        $url = "https://api.mercadopago.com/v1/payments/{$paymentId}?access_token={$access_token_mp}&status=approved&offset=0&limit=10";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_SSL_VERIFYHOST => false,
            // CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true); 

    }


    public function setErrorLog($exception)
    {
        Log::error("Line: {$exception->getLine()} - Message: {$exception->getMessage()} - File: {$exception->getFile()}");
    }

        
    /**
     * @return Company
     */
    public function getSoapTypeId()
    {
        return Company::select('soap_type_id')->firstOrFail()->soap_type_id;
    }

}
