<?php

    namespace App\Models\Tenant;

    use Illuminate\Database\Eloquent\Builder;

    /**
     * App\Models\Tenant\PaymentCondition
     *
     * @property string $name
     * @method static Builder|PaymentCondition newModelQuery()
     * @method static Builder|PaymentCondition newQuery()
     * @method static Builder|PaymentCondition query()
     * @mixin ModelTenant
     * @mixin \Eloquent
     * 
     * Usado en:
     * App\Models\Tenant\Purchase
     */
    
    class GeneralPaymentCondition extends ModelTenant
    {
        public $timestamps = false;
        public $incrementing = false;

        protected $fillable = [
            'id',
            'name',
        ];

    }
