<?php

namespace Modules\MercadoPago\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MercadoPago\Models\Transaction;
use MercadoPago\SDK;
use MercadoPago\Payment;
use Modules\MercadoPago\Traits\UtilityTrait;
use Modules\MercadoPago\Http\Resources\TransactionCollection;
use Illuminate\Support\Str;
use Exception;
use Modules\MercadoPago\Http\Requests\TransactionRequest;
use Modules\Payment\Models\{
    PaymentConfiguration,
    PaymentLink,
};


class TransactionController extends Controller
{ 
   
    use UtilityTrait;

    protected $payment_link;
    protected $transaction;
    protected $response;


    public function store(TransactionRequest $request)
    {
        
        try {
            
            $access_token_mp = PaymentConfiguration::getAccessTokenMp();
            $validator = $this->validateStore($request, $access_token_mp);
            
            if(!$validator['success']){
                return $validator;
            }

            $record = DB::connection()->transaction(function () use ($request, $access_token_mp) {
                
                $this->payment_link = PaymentLink::findOrFail($request->payment_link_id); 

                SDK::setAccessToken($access_token_mp);

                $payment = new Payment();

                $payment->transaction_amount = $request->transaction_amount;
                $payment->token = $request->token;
                $payment->description = $request->description;
                $payment->installments = $request->installments;
                $payment->payment_method_id = $request->payment_method_id;

                $payment->payer = [
                    "email" => $request->email
                ];

                $payment->save();

                $transaction_query = null;

                if($payment->id)
                {
                    sleep(5);
                    $transaction_query = $this->transactionQuery($payment->id, $access_token_mp);
                }
                else
                {
                    throw new Exception($payment->error->message ?? 'Error desconocido');
                }

                $this->transaction = $this->saveTransaction($transaction_query, $request);

                return $this->setResponse($payment);
                
            });
            
            return $record;

        } catch (Exception $e) {

            $this->setErrorLog($e);
            return $this->getErrorMessage('Lo sentimos, ocurrió un error inesperado: '.$e->getMessage());

        }

    }
     

    public function validateStore($request, $access_token_mp)
    {

        // if(strcmp($request->payment_type_id, 'credit_card') !== 0){
        //     return $this->getErrorMessage('Solo es permitido el uso de tarjetas de crédito');
        // }

        if(!$access_token_mp){
            return $this->getErrorMessage('Datos de configuración incorrectos, comuníquese con el administrador');
        }

        // if($request->transaction_amount > 2000){
        //     return $this->getErrorMessage('No puede realizar transacciones con montos mayores a 2000 soles');
        // }

        return [
            'success' => true
        ];
        
    }


    private function setResponse($payment)
    {

        $success_operation = false; 
        $transaction_state_id = null; 
        $transaction_state_message = null; 

        if($this->transaction)
        {
            $success_operation = (bool) $this->transaction->transaction_state->success; 
            $transaction_state_id = $this->transaction->transaction_state_id; 
            $message = $success_operation ? 'Transacción registrada correctamente' : 'Transacción registrada con errores';
            $transaction_state_message = $this->transaction->transaction_state->user_message;
            
        }else
        {
            $message = $this->getTransactionState($payment->status, $payment->status_detail)->user_message;
        }

        return [
            'success' => true,
            'success_operation' => $success_operation,
            'message' => $message,
            'transaction_state_message' => $transaction_state_message,
            'transaction_state_id' => $transaction_state_id,
            // 'data' => [
            //     'transaction' => $this->transaction,
            //     'transaction_queries' => $this->transaction->transaction_queries,
            // ]
        ];

    }


    private function saveTransaction($transaction_query, $request)
    {

        $transaction_state = $this->getTransactionState($transaction_query['status'], $transaction_query['status_detail']);

        if($transaction_state)
        {
            $payment_id = isset($transaction_query['id']) ? $transaction_query['id'] : null;

            $transaction = $this->payment_link->transactions()->create([
                'soap_type_id' => $this->getSoapTypeId(),
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'uuid' => Str::uuid()->toString(),
                'description' => $request->description,
                'payment_id' => $payment_id,
                'transaction_state_id' => $transaction_state->id,
                'amount' => $request->transaction_amount,
            ]);


            if($payment_id)
            {
                $transaction->transaction_queries()->create([
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'response' => $transaction_query
                ]);
            }

            return $transaction;
        }

        return null;
    }


    public function transactionQuery($paymentId, $access_token_mp)
    {
        return $this->searchPayment($paymentId, $access_token_mp); 
    }


    // public function records(Request $request)
    // {
        
    //     $transactions = Transaction::whereUserCustomer()->latest()
    //                                 ->paginate(config('system_configuration.items_per_page'));

    //     return new TransactionCollection($transactions);

    // }

}
