<?php


    namespace Modules\Expense\Models;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\PaymentMethodType;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
 * Class BankLoanPayment
 *
 * @property int                    $id
 * @property int                    $bank_loan_id
 * @property Carbon                 $date
 * @property string                 $currency_type_id
 * @property float                  $amount
 * @property string|null            $payment_method_type_id
 * @property BankLoan               $bank_loan
 * @property CurrencyType           $currency_type
 * @property PaymentMethodType|null $payment_method_type
 * @mixin ModelTenant
 * @package Modules\Expense\Models
 * @method static \Illuminate\Database\Eloquent\Builder|BankLoanFee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankLoanFee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BankLoanFee query()
 */
    class BankLoanFee extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;
        protected $table = 'bank_loan_fee';
        protected $casts = [
            'bank_loan_id' => 'int',
            'amount' => 'float'
        ];


        protected $fillable = [
            'bank_loan_id',
            'date',
            'currency_type_id',
            'amount',
            'payment_method_type_id'
        ];

        /**
         * @return BelongsTo
         */
        public function bank_loan()
        {
            return $this->belongsTo(BankLoan::class);
        }

        /**
         * @return int
         */
        public function getBankLoanId(): int
        {
            return $this->bank_loan_id;
        }

        /**
         * @param int $bank_loan_id
         *
         * @return BankLoanFee
         */
        public function setBankLoanId(int $bank_loan_id): BankLoanFee
        {
            $this->bank_loan_id = $bank_loan_id;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getDate(): Carbon
        {
            return $this->date;
        }

        /**
         * @param Carbon $date
         *
         * @return BankLoanFee
         */
        public function setDate(Carbon $date): BankLoanFee
        {
            $this->date = $date;
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
         * @return BankLoanFee
         */
        public function setCurrencyTypeId(string $currency_type_id): BankLoanFee
        {
            $this->currency_type_id = $currency_type_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getAmount(): float
        {
            return $this->amount;
        }

        /**
         * @param float $amount
         *
         * @return BankLoanFee
         */
        public function setAmount(float $amount): BankLoanFee
        {
            $this->amount = $amount;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPaymentMethodTypeId(): ?string
        {
            return $this->payment_method_type_id;
        }

        /**
         * @param string|null $payment_method_type_id
         *
         * @return BankLoanFee
         */
        public function setPaymentMethodTypeId(?string $payment_method_type_id): BankLoanFee
        {
            $this->payment_method_type_id = $payment_method_type_id;
            return $this;
        }

        /**
         * @return BankLoan
         */
        public function getBankLoan(): BankLoan
        {
            return $this->bank_loan;
        }

        /**
         * @param BankLoan $bank_loan
         *
         * @return BankLoanFee
         */
        public function setBankLoan(BankLoan $bank_loan): BankLoanFee
        {
            $this->bank_loan = $bank_loan;
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
         * @return BankLoanFee
         */
        public function setCurrencyType(CurrencyType $currency_type): BankLoanFee
        {
            $this->currency_type = $currency_type;
            return $this;
        }

        /**
         * @return PaymentMethodType|null
         */
        public function getPaymentMethodType(): ?PaymentMethodType
        {
            return $this->payment_method_type;
        }

        /**
         * @param PaymentMethodType|null $payment_method_type
         *
         * @return BankLoanFee
         */
        public function setPaymentMethodType(?PaymentMethodType $payment_method_type): BankLoanFee
        {
            $this->payment_method_type = $payment_method_type;
            return $this;
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
        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class, 'payment_method_type_id', 'id');
        }
    }

