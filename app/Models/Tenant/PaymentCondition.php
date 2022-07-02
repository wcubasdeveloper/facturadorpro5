<?php

    namespace App\Models\Tenant;

    use Illuminate\Database\Eloquent\Builder;

    /**
     * App\Models\Tenant\PaymentCondition
     *
     * @property string $name
     * @property int    $int
     * @property int    $days
     * @property bool   $is_locked
     * @property bool   $is_active
     * @method static Builder|PaymentCondition newModelQuery()
     * @method static Builder|PaymentCondition newQuery()
     * @method static Builder|PaymentCondition query()
     * @mixin ModelTenant
     * @mixin \Eloquent
     */
    class PaymentCondition extends ModelTenant
    {
        public $timestamps = false;
        public $incrementing = false;

        protected $fillable = [
            'id',
            'name',
            'days',
            'is_locked',
            'is_active',
        ];

        protected $casts = [
            'id' => 'string',
            'days' => 'int',
            'is_locked' => 'bool',
            'is_active' => 'bool',
        ];
    }
