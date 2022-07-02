<?php


    namespace Modules\Expense\Models;

    use App\Models\Tenant\Bank;
    use App\Models\Tenant\BankAccount;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\StateType;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class BankLoan
     *
     * @property int                          $id
     * @property int                          $user_id
     * @property int                          $bank_loan_type_id
     * @property int                          $establishment_id
     * @property int                          $bank_id
     * @property string                       $currency_type_id
     * @property string                       $external_id
     * @property int                          $number
     * @property Carbon                       $date_of_issue
     * @property Carbon                       $time_of_issue
     * @property string|null                  $bank
     * @property float|null                   $exchange_rate_sale
     * @property float|null                   $total
     * @property float|null                   $total_interest
     * @property float|null                   $total_ingress
     * @property string|null                  $soap_type_id
     * @property string|null                  $state_type_id
     * @property Carbon|null                  $created_at
     * @property Carbon|null                  $updated_at
     * @property BankLoanType                 $bank_loan_type
     * @property CurrencyType                 $currency_type
     * @property Establishment                $establishment
     * @property SoapType|null                $soap_type
     * @property StateType|null               $state_type
     * @property User                         $user
     * @property Collection|BankLoanFee[]     $bank_loan_fees
     * @property Collection|BankLoanItem[]    $bank_loan_items
     * @property Collection|BankLoanPayment[] $bank_loan_payments
     * @property Collection|BankLoanPayment[] $payments
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @property-read int|null                $bank_loan_items_count
     * @property-read int|null                $bank_loan_payments_count
     * @method static Builder|BankLoan newModelQuery()
     * @method static Builder|BankLoan newQuery()
     * @method static Builder|BankLoan query()
     */
    class BankLoan extends ModelTenant
    {
        use UsesTenantConnection;


        protected $casts = [
            'user_id' => 'int',
            'bank_loan_type_id' => 'int',
            'establishment_id' => 'int',
            'bank_id' => 'int',
            'bank_account_id' => 'int',
            'number' => 'int',
            'exchange_rate_sale' => 'float',
            'total' => 'float',
            'total_interest' => 'float',
            'total_ingress' => 'float',
            'date_of_issue' => 'date',


        ];
        protected $fillable = [
            'user_id',
            'bank_loan_type_id',
            'establishment_id',
            'bank_account_id',
            'bank_id',
            'currency_type_id',
            'external_id',
            'number',
            'date_of_issue',
            'time_of_issue',
            'bank',
            'exchange_rate_sale',
            'total',
            'total_interest',
            'total_ingress',
            'soap_type_id',
            'state_type_id',
        ];
        /*
        protected static function boot()
        {

            parent::boot();

            static::saving(function (self $model) {
                if(empty($model->bank)) {
                    $model->bank = [];
                }
                if(\is_array($model->bank) ){
                    $model->bank = json_encode($model->bank);
                }
            });

        }
*/

        public function setBankAttribute($value)
        {
            $this->attributes['bank'] = (null === $value) ? null : json_encode($value);
        }

        public function getBankAttribute($value)
        {
            return (null === $value) ? null : (object)json_decode($value);
        }
        /**
         * @return float
         */
        public function getTotalInterest(): float
        {
            return $this->total_interest;
        }

        /**
         * @param float $total_interest
         *
         * @return BankLoan
         */
        public function setTotalInterest(float $total_interest): BankLoan
        {
            $this->total_interest = $total_interest;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalIngress(): float
        {
            return $this->total_ingress;
        }

        /**
         * @param float $total_ingress
         *
         * @return BankLoan
         */
        public function setTotalIngress(float $total_ingress): BankLoan
        {
            $this->total_ingress = $total_ingress;
            return $this;
        }

        /**
         * @return BelongsTo
         */
        public function bank()
        {
            return $this->belongsTo(Bank::class);
        }

        /**
         * @return BelongsTo
         */
        public function bank_account()
        {
            return $this->belongsTo(BankAccount::class);
        }

        /**
         * @return BelongsTo
         */
        public function bank_loan_type()
        {
            return $this->belongsTo(BankLoanType::class);
        }

        /**
         * @return BelongsTo
         */
        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id', 'id');
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
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class, 'soap_type_id', 'id');
        }

        /**
         * @return BelongsTo
         */
        public function state_type()
        {
            return $this->belongsTo(StateType::class, 'state_type_id', 'id');
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return HasMany
         */
        public function items(): HasMany
        {
            return $this->bank_loan_items();

        }

        /**
         * @return HasMany
         */
        public function bank_loan_items()
        {
            return $this->hasMany(BankLoanItem::class);
        }

        /**
         * @return HasMany
         */
        public function bank_loan_payments()
        {
            return $this->hasMany(BankLoanPayment::class);
        }

        /**
         * @return HasMany
         */
        public function payments()
        {
            return $this->hasMany(BankLoanPayment::class);
        }

        /**
         * @return int
         */
        public function getUserId(): int
        {
            return $this->user_id;
        }

        /**
         * @param int $user_id
         *
         * @return BankLoan
         */
        public function setUserId(int $user_id): BankLoan
        {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getBankLoanTypeId(): int
        {
            return $this->bank_loan_type_id;
        }

        /**
         * @param int $bank_loan_type_id
         *
         * @return BankLoan
         */
        public function setBankLoanTypeId(int $bank_loan_type_id): BankLoan
        {
            $this->bank_loan_type_id = $bank_loan_type_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getEstablishmentId(): int
        {
            return $this->establishment_id;
        }

        /**
         * @param int $establishment_id
         *
         * @return BankLoan
         */
        public function setEstablishmentId(int $establishment_id): BankLoan
        {
            $this->establishment_id = $establishment_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getBankId(): int
        {
            return $this->bank_id;
        }

        /**
         * @param int $bank_id
         *
         * @return BankLoan
         */
        public function setBankId(int $bank_id): BankLoan
        {
            $this->bank_id = $bank_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getCurrencyTypeId(): string
        {
            return $this->currency_type_id;
        }

        /**
         * @param string $currency_type_id
         *
         * @return BankLoan
         */
        public function setCurrencyTypeId(string $currency_type_id): BankLoan
        {
            $this->currency_type_id = $currency_type_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getExternalId(): string
        {
            return $this->external_id;
        }

        /**
         * @param string $external_id
         *
         * @return BankLoan
         */
        public function setExternalId(string $external_id): BankLoan
        {
            $this->external_id = $external_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getNumber(): int
        {
            return $this->number;
        }

        /**
         * @param int $number
         *
         * @return BankLoan
         */
        public function setNumber(int $number): BankLoan
        {
            $this->number = $number;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getDateOfIssue(): Carbon
        {
            return $this->date_of_issue;
        }

        /**
         * @param Carbon $date_of_issue
         *
         * @return BankLoan
         */
        public function setDateOfIssue(Carbon $date_of_issue): BankLoan
        {
            $this->date_of_issue = $date_of_issue;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getTimeOfIssue(): Carbon
        {
            return $this->time_of_issue;
        }

        /**
         * @param Carbon $time_of_issue
         *
         * @return BankLoan
         */
        public function setTimeOfIssue(Carbon $time_of_issue): BankLoan
        {
            $this->time_of_issue = $time_of_issue;
            return $this;
        }

        /**
         * @return string
         */
        public function getBank(): string
        {
            return $this->bank;
        }

        /**
         * @param string $bank
         *
         * @return BankLoan
         */
        public function setBank(string $bank): BankLoan
        {
            $this->bank = $bank;
            return $this;
        }

        /**
         * @return float
         */
        public function getExchangeRateSale(): float
        {
            return $this->exchange_rate_sale;
        }

        /**
         * @param float $exchange_rate_sale
         *
         * @return BankLoan
         */
        public function setExchangeRateSale(float $exchange_rate_sale): BankLoan
        {
            $this->exchange_rate_sale = $exchange_rate_sale;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotal(): float
        {
            return $this->total;
        }

        /**
         * @param float $total
         *
         * @return BankLoan
         */
        public function setTotal(float $total): BankLoan
        {
            $this->total = $total;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getSoapTypeId(): ?string
        {
            return $this->soap_type_id;
        }

        /**
         * @param string|null $soap_type_id
         *
         * @return BankLoan
         */
        public function setSoapTypeId(?string $soap_type_id): BankLoan
        {
            $this->soap_type_id = $soap_type_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getStateTypeId(): ?string
        {
            return $this->state_type_id;
        }

        /**
         * @param string|null $state_type_id
         *
         * @return BankLoan
         */
        public function setStateTypeId(?string $state_type_id): BankLoan
        {
            $this->state_type_id = $state_type_id;
            return $this;
        }

        /**
         * @return BankLoanType
         */
        public function getBankLoanType(): BankLoanType
        {
            return $this->bank_loan_type;
        }

        /**
         * @param BankLoanType $bank_loan_type
         *
         * @return BankLoan
         */
        public function setBankLoanType(BankLoanType $bank_loan_type): BankLoan
        {
            $this->bank_loan_type = $bank_loan_type;
            return $this;
        }

        /**
         * @return CurrencyType
         */
        public function getCurrencyType(): CurrencyType
        {
            return $this->currency_type;
        }

        /**
         * @param CurrencyType $currency_type
         *
         * @return BankLoan
         */
        public function setCurrencyType(CurrencyType $currency_type): BankLoan
        {
            $this->currency_type = $currency_type;
            return $this;
        }

        /**
         * @return Establishment
         */
        public function getEstablishment(): Establishment
        {
            return $this->establishment;
        }

        /**
         * @param Establishment $establishment
         *
         * @return BankLoan
         */
        public function setEstablishment(Establishment $establishment): BankLoan
        {
            $this->establishment = $establishment;
            return $this;
        }

        /**
         * @return SoapType|null
         */
        public function getSoapType(): ?SoapType
        {
            return $this->soap_type;
        }

        /**
         * @param SoapType|null $soap_type
         *
         * @return BankLoan
         */
        public function setSoapType(?SoapType $soap_type): BankLoan
        {
            $this->soap_type = $soap_type;
            return $this;
        }

        /**
         * @return StateType|null
         */
        public function getStateType(): ?StateType
        {
            return $this->state_type;
        }

        /**
         * @param StateType|null $state_type
         *
         * @return BankLoan
         */
        public function setStateType(?StateType $state_type): BankLoan
        {
            $this->state_type = $state_type;
            return $this;
        }

        /**
         * @return User
         */
        public function getUser(): User
        {
            return $this->user;
        }

        /**
         * @param User $user
         *
         * @return BankLoan
         */
        public function setUser(User $user): BankLoan
        {
            $this->user = $user;
            return $this;
        }

        /**
         * @return Collection|BankLoanItem[]
         */
        public function getBankLoanItems()
        {
            return $this->bank_loan_items;
        }

        /**
         * @param Collection|BankLoanItem[] $bank_loan_items
         *
         * @return BankLoan
         */
        public function setBankLoanItems($bank_loan_items)
        {
            $this->bank_loan_items = $bank_loan_items;
            return $this;
        }

        /**
         * @return Collection|BankLoanPayment[]
         */
        public function getBankLoanPayments()
        {
            return $this->bank_loan_payments;
        }

        /**
         * @param Collection|BankLoanPayment[] $bank_loan_payments
         *
         * @return BankLoan
         */
        public function setBankLoanPayments($bank_loan_payments)
        {
            $this->bank_loan_payments = $bank_loan_payments;
            return $this;
        }


        public function bank_loan_fees()
        {
            return $this->hasMany(BankLoanFee::class);
        }

        public function fee()
        {
            return $this->hasMany(BankLoanFee::class);
        }

        /**
         * @param Builder $query
         *
         * @return Builder|null
         */
        public function scopeWhereTypeUser(Builder $query, $params= [])
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
            return ($user->type === 'seller') ? $query->where('user_id', $user->id) : $query;
        }
        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereStateTypeAccepted($query)
        {
            return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
        }
        /**
         * @return string
         */
        public function getNumberFullAttribute()
        {
            return  $this->number;
        }

        /**
         * @return string
         */
        public function getNumberFull()
        {
            return  $this->getNumberFullAttribute();
        }

    }


