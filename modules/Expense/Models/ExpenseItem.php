<?php

    namespace Modules\Expense\Models;

    use App\Models\Tenant\ModelTenant;
    use App\Traits\AttributePerItems;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class ExpenseItem
     *
     * @property int     $id
     * @property int     $expense_id
     * @property string  $description
     * @property float   $total
     * @property Expense $expense
     *
     * @mixin ModelTenant
     *
     * @method static Builder|ExpenseItem newModelQuery()
     * @method static Builder|ExpenseItem newQuery()
     * @method static Builder|ExpenseItem query()
     */
    class ExpenseItem extends ModelTenant
    {
        use UsesTenantConnection;
        use AttributePerItems;

        public $timestamps = false;


        protected $casts = [
            'expense_id' => 'int',
            'total' => 'float'
        ];

        protected $fillable = [
            'expense_id',
            'description',
            'total',
        ];

        /**
         * @return BelongsTo
         */
        public function expense()
        {
            return $this->belongsTo(Expense::class);
        }

    }
