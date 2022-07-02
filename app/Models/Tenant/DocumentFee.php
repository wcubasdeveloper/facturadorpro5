<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

/**
 * Class DocumentFee
 *
 * @package App\Models\Tenant
 */
class DocumentFee extends ModelTenant
{
    public $timestamps = false;
    protected $table = 'document_fee';

    protected $fillable = [
        'document_id',
        'date',
        'currency_type_id',
        'payment_method_type_id',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'float',
    ];

    /**
     * @return mixed
     */
    public function getPaymentMethodTypeId() {
        return $this->payment_method_type_id;
    }

    /**
     * @param mixed $payment_method_type_id
     *
     * @return DocumentFee
     */
    public function setPaymentMethodTypeId($payment_method_type_id) {
        $this->payment_method_type_id = $payment_method_type_id;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class, 'payment_method_type_id');
    }

    /**
     * Devuelve el nombre del metodo de pago
     *
     * @return string
     */
    public function getStringPaymentMethodType(){

        $payment_method_type = PaymentMethodType::where('id',$this->payment_method_type_id)->first();
        $return = null;
        if(!empty($payment_method_type)){
            $return =  $payment_method_type->getDescription();
        }
        return $return;
    }
}
