<?php

    namespace Modules\Expense\Models;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\StateType;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class Modules\Expense\Models\Expense
     *
     * @property int                              $id
     * @property int                              $user_id
     * @property string|null                      $soap_type_id
     * @property int                              $expense_type_id
     * @property int                              $establishment_id
     * @property int                              $supplier_id
     * @property int                              $expense_reason_id
     * @property string                           $currency_type_id
     * @property string                           $external_id
     * @property string|null                      $state_type_id
     * @property string|null                      $number
     * @property Carbon                           $date_of_issue
     * @property Carbon                           $time_of_issue
     * @property string                           $supplier
     * @property float                            $exchange_rate_sale
     * @property float                            $total
     * @property Carbon|null                      $created_at
     * @property Carbon|null                      $updated_at
     * @property CurrencyType                     $currency_type
     * @property Establishment                    $establishment
     * @property ExpenseReason                    $expense_reason
     * @property ExpenseType                      $expense_type
     * @property SoapType|null                    $soap_type
     * @property StateType|null                   $state_type
     * @property User                             $user
     * @property Collection|ExpenseItem[]         $expense_items
     * @property Collection|ExpensePayment[]      $expense_payments
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @property-read ExpenseType                 $document_type
     * @property-read mixed                       $number_full
     * @property-read Collection|ExpenseItem[]    $items
     * @property-read int|null                    $items_count
     * @property-read Collection|ExpensePayment[] $payments
     * @property-read int|null                    $payments_count
     * @property-read int|null                    $expense_items_count
     * @property-read int|null                    $expense_payments_count
     * @method static Builder|Expense newModelQuery()
     * @method static Builder|Expense newQuery()
     * @method static Builder|Expense query()
     * @method static Builder|Expense whereStateTypeAccepted()
     * @method static Builder|Expense whereTypeUser()
     */
    class Expense extends ModelTenant
    {
        use UsesTenantConnection;

        // protected $with = ['user', 'items'];

        protected $fillable = [
            'user_id',
            'soap_type_id',
            'expense_type_id',
            'expense_reason_id',
            'establishment_id',
            'supplier_id',
            'currency_type_id',
            'external_id',
            'state_type_id',
            'number',
            'date_of_issue',
            'time_of_issue',
            'supplier',
            'exchange_rate_sale',
            'total',
        ];

        protected $casts = [
            'date_of_issue' => 'date',
            'user_id' => 'int',
            'expense_type_id' => 'int',
            'establishment_id' => 'int',
            'supplier_id' => 'int',
            'expense_reason_id' => 'int',
            'exchange_rate_sale' => 'float',
            'total' => 'float'
        ];

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getSupplierAttribute($value)
        {
            return (null === $value) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setSupplierAttribute($value)
        {
            $this->attributes['supplier'] = (null === $value) ? null : json_encode($value);
        }

        /**
         * @return BelongsTo
         */
        public function supplier()
        {
            return $this->belongsTo(Person::class, 'supplier_id');
        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(ExpenseItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }

        /**
         * @return BelongsTo
         */
        public function state_type()
        {
            return $this->belongsTo(StateType::class);
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return BelongsTo
         */
        public function expense_reason()
        {
            return $this->belongsTo(ExpenseReason::class);
        }

        /**
         * @return BelongsTo
         */
        public function establishment()
        {
            return $this->belongsTo(Establishment::class);
        }

        /**
         * @return BelongsTo
         */
        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function expense_type()
        {
            return $this->belongsTo(ExpenseType::class);
        }

        /**
         * @return HasMany
         */
        public function payments()
        {
            return $this->hasMany(ExpensePayment::class);
        }

        /**
         * @param Builder $query
         *
         * @return Builder|null
         */
        public function scopeWhereTypeUser(Builder $query, $params = [])
        {
            if(isset($params['user_id'])) {
                $user_id = (int)$params['user_id'];
                $user = User::find($user_id);
                if(!$user) {
                    $user = new User();
                }
            }
            else { 
                $user = auth()->user();
            }
            return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }

        public function getNumberFullAttribute()
        {
            return $this->number;
        }

        /**
         * @return BelongsTo
         */
        public function document_type()
        {
            return $this->belongsTo(ExpenseType::class, 'expense_type_id');
        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeWhereStateTypeAccepted(Builder $query)
        {
            return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
        }


        /**
         * @return HasMany
         */
        public function expense_items()
        {
            return $this->hasMany(ExpenseItem::class);
        }

        /**
         * @return HasMany
         */
        public function expense_payments()
        {
            return $this->hasMany(ExpensePayment::class);
        }

        
        /**
         * 
         * Validar si el registro esta rechazado o anulado
         * 
         * @return bool
         */
        public function isVoidedOrRejected()
        {
            return in_array($this->state_type_id, self::VOIDED_REJECTED_IDS);
        }

    }
