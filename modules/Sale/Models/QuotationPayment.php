<?php

namespace Modules\Sale\Models;

use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\CardBrand;
use App\Models\Tenant\ModelTenant;
use Modules\Finance\Models\PaymentFile;
use App\Models\Tenant\{
    Cash,
};


class QuotationPayment extends ModelTenant
{
    protected $with = ['payment_method_type', 'card_brand'];
    public $timestamps = false;

    protected $fillable = [
        'quotation_id',
        'date_of_payment',
        'payment_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'change',
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
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    
    public function payment_file()
    {
        return $this->morphOne(PaymentFile::class, 'payment');
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
            'type' => 'quotation',
            'type_transaction' => 'income',
            'type_transaction_description' => 'Venta (Pago a cuenta)',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->customer->name,
            'acquirer_number' => $this->associated_record_payment->customer->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->getDocumentTypeDescription(),
            'payment_method_type_id' => $this->payment_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,
        ];
    }

}