<?php

    namespace Modules\Expense\Models;

    use App\Models\Tenant\CardBrand;
    use App\Models\Tenant\CashDocument;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\PaymentMethodType;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphOne;
    use Modules\Finance\Models\GlobalPayment;

    /**
     * Class BankLoanPayment
     *
     * @property int                                   $id
     * @property int                                   $bank_loan_id
     * @property Carbon|null                           $date_of_payment
     * @property string                                $payment_method_type_id
     * @property bool                                  $has_card
     * @property string|null                           $card_brand_id
     * @property string|null                           $reference
     * @property float                                 $payment
     * @property BankLoan                  $bank_loan
     * @property CardBrand|null            $card_brand
     * @property PaymentMethodType         $payment_method_type
     * @property Collection|CashDocument[] $cash_documents
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @property-read int|null             $cash_documents_count
     * @method static Builder|BankLoanPayment newModelQuery()
     * @method static Builder|BankLoanPayment newQuery()
     * @method static Builder|BankLoanPayment query()
     * @property-read BankLoan             $associated_record_payment
     * @property-read GlobalPayment|null   $global_payment
     */
    class BankLoanPayment extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;
        protected $casts = [
            'bank_loan_id' => 'int',
            'has_card' => 'bool',
            'payment' => 'float',
            'date_of_payment' => 'date',
        ];
        protected $fillable = [
            'bank_loan_id',
            'date_of_payment',
            'payment_method_type_id',
            'has_card',
            'card_brand_id',
            'reference',
            'payment',
        ];

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
         * @return BankLoanPayment
         */
        public function setBankLoanId(int $bank_loan_id): BankLoanPayment
        {
            $this->bank_loan_id = $bank_loan_id;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getDateOfPayment(): Carbon
        {
            return $this->date_of_payment;
        }

        /**
         * @param Carbon $date_of_payment
         *
         * @return BankLoanPayment
         */
        public function setDateOfPayment(Carbon $date_of_payment): BankLoanPayment
        {
            $this->date_of_payment = $date_of_payment;
            return $this;
        }


        /**
         * @return bool
         */
        public function isHasCard(): bool
        {
            return $this->has_card;
        }

        /**
         * @param bool $has_card
         *
         * @return BankLoanPayment
         */
        public function setHasCard(bool $has_card): BankLoanPayment
        {
            $this->has_card = $has_card;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getCardBrandId(): ?string
        {
            return $this->card_brand_id;
        }

        /**
         * @param string|null $card_brand_id
         *
         * @return BankLoanPayment
         */
        public function setCardBrandId(?string $card_brand_id): BankLoanPayment
        {
            $this->card_brand_id = $card_brand_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getReference(): ?string
        {
            return $this->reference;
        }

        /**
         * @param string|null $reference
         *
         * @return BankLoanPayment
         */
        public function setReference(?string $reference): BankLoanPayment
        {
            $this->reference = $reference;
            return $this;
        }

        /**
         * @return float
         */
        public function getPayment(): float
        {
            return $this->payment;
        }

        /**
         * @param float $payment
         *
         * @return BankLoanPayment
         */
        public function setPayment(float $payment): BankLoanPayment
        {
            $this->payment = $payment;
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
         * @return BankLoanPayment
         */
        public function setBankLoan(BankLoan $bank_loan): BankLoanPayment
        {
            $this->bank_loan = $bank_loan;
            return $this;
        }

        /**
         * @return CardBrand|null
         */
        public function getCardBrand(): ?CardBrand
        {
            return $this->card_brand;
        }

        /**
         * @param CardBrand|null $card_brand
         *
         * @return BankLoanPayment
         */
        public function setCardBrand(?CardBrand $card_brand): BankLoanPayment
        {
            $this->card_brand = $card_brand;
            return $this;
        }

        /**
         * @return CashDocument[]|Collection
         */
        public function getCashDocuments()
        {
            return $this->cash_documents;
        }

        /**
         * @param CashDocument[]|Collection $cash_documents
         *
         * @return BankLoanPayment
         */
        public function setCashDocuments($cash_documents)
        {
            $this->cash_documents = $cash_documents;
            return $this;
        }

        /**
         * @return BelongsTo
         */
        public function bank_loan()
        {
            return $this->belongsTo(BankLoan::class);
        }

        /**
         * @return BelongsTo
         */
        public function card_brand()
        {
            return $this->belongsTo(CardBrand::class, 'card_brand_id', 'id');
        }

        /**
         * @return HasMany
         */
        public function cash_documents()
        {
            return $this->hasMany(CashDocument::class);
        }


        /**
         * @return BelongsTo
         */
        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class, 'payment_method_type_id', 'id');
        }

        /**
         * @return MorphOne
         */
        public function global_payment()
        {
            return $this->morphOne(GlobalPayment::class, 'payment');
        }


        /**
         * @return BelongsTo
         */
        public function associated_record_payment()
        {
            return $this->belongsTo(BankLoan::class, 'bank_loan_id');
        }

    }



