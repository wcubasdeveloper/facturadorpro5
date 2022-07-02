<?php

    namespace Modules\Expense\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;


    /**
     * Class ExpenseMethodType
     *
     * @property int                         $id
     * @property string                      $description
     * @property bool                        $has_card
     * @property Collection|ExpensePayment[] $expense_payments
     * @property-read int|null               $expense_payments_count
     * @mixin ModelTenant
     * @package App\Models
     * @method static Builder|ExpenseMethodType newModelQuery()
     * @method static Builder|ExpenseMethodType newQuery()
     * @method static Builder|ExpenseMethodType query()
     * @method static Builder|ExpenseMethodType whereFilterPayments($params)
     */
    class ExpenseMethodType extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;

        protected $fillable = [
            'description',
            'has_card',
        ];
        /*
        protected $casts = [
            'has_card' => 'bool'
        ];
        */

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function expense_payments()
        {
            return $this->hasMany(ExpensePayment::class, 'expense_method_type_id');
        }


        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param                                       $params
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereFilterPayments(Builder $query, $params)
        {

            return $query->with(['expense_payments' => function ($q) use ($params) {
                $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    ->whereHas('associated_record_payment', function ($p) {
                        $p->whereStateTypeAccepted()->whereTypeUser();
                    });
            }]);

        }
    }
