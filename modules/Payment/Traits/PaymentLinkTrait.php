<?php

namespace Modules\Payment\Traits;

use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\{
    PaymentLink,
    PaymentLinkType,
    PaymentConfiguration,
};
use App\Models\Tenant\{
    Company
};


trait PaymentLinkTrait
{ 

    /**
     * 
     * Buscar link de pago y retornar datos publicos
     *
     * @return PaymentLink
     */
    public function getPublicPaymentLink($payment_link_type_id, $uuid)
    {
        return PaymentLink::whereFilterPublicData($payment_link_type_id, $uuid)->firstOrFail()->getFormPublicData();
    }

    
    /**
     *
     * @param  PaymentLink $payment_link
     * @param  float $input_total
     * @return float
     */
    public function getTotal($payment_link, $input_total, &$apply_conversion)
    {

        // si el link de pago es generado a partir de un pago, no se puede modificar el total por url
        if($payment_link['has_payment'])
        {
            $associated_record_payment = $payment_link['associated_record_payment'];
    
            if($associated_record_payment['currency_type_id'] === 'PEN') return $payment_link['total'];
    
            $apply_conversion = true;
    
            return round($payment_link['total'] * $associated_record_payment['exchange_rate_sale'], 2);
        }

        return $input_total;
    }
    

    /**
     *
     * @return Company
     */
    public function getPublicDataCompany()
    {
        return Company::select('name', 'number')->first();
    }

    
    /**
     * 
     * Validar datos
     *
     * @param  string $payment_link_type_id
     * @param  float $total
     * @return void
     */
    public function validatePublicParams($payment_link_type_id, $total)
    {

        $validate = [
            'success' => true,
            'message' => null,
        ];

        if(!is_numeric($total))
        {
            $validate = [
                'success' => false,
                'message' => 'El total debe ser númerico',
            ];
        }
        else
        {
            if($total <= 0)
            {
                $validate = [
                    'success' => false,
                    'message' => 'El total debe ser mayor a 0',
                ];
            }
        }

        if(!PaymentLinkType::find($payment_link_type_id))
        {
            $validate = [
                'success' => false,
                'message' => 'Tipo de link no permitido',
            ]; 
        }
        
        if(!$validate['success']) throw new Exception($validate['message']);

    }

}
