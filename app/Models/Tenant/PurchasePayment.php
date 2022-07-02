<?php

namespace App\Models\Tenant;

use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\PaymentFile;

class PurchasePayment extends ModelTenant
{
    protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'purchase_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }
    
    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }

    public function associated_record_payment()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function payment_file()
    {
        return $this->morphOne(PaymentFile::class, 'payment');
    }
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    

    /**
     * 
     * Filtros para obtener pagos en efectivo y con destino caja
     *
     * @param  Builder $query
     * @return Collection
     */
    public function scopeWhereFilterCashPayment($query)
    {
        return $query->where('payment_method_type_id', PaymentMethodType::CASH_PAYMENT_ID)
                    ->whereHas('global_payment', function($query){
                        return $query->where('destination_type', Cash::class);
                    });
    }

    
    /**
     * 
     * Obtener informacion del pago y registro origen relacionado
     *
     * @return array
     */
    public function getRowResourceCashPayment()
    {
        return [
            'type' => 'purchase',
            'type_transaction' => 'egress',
            'type_transaction_description' => 'Compra',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->supplier->name,
            'acquirer_number' => $this->associated_record_payment->supplier->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->document_type->description,
            'payment_method_type_id' => $this->payment_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,
        ];
    }


}