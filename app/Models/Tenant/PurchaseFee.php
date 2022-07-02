<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

/**
 * Class PurchaseFee
 *
 * @package App\Models\Tenant
 */
class PurchaseFee extends ModelTenant
{
    public $timestamps = false;
    protected $table = 'purchase_fee';

    protected $fillable = [
        'purchase_id',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
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
     * Devuelve la descripcion del metodo de pago
     *
     * @return string
     */
    public function getStringPaymentMethodType()
    {
        return optional($this->payment_method_type)->getDescription();
    }

}
