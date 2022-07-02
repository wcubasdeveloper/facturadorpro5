<?php



    namespace Modules\Expense\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class BankLoanItem
     *
     * @property int $id
     * @property int $bank_loan_id
     * @property string $description
     * @property float|null $total
     * @property float|null $total_interest
     * @property float|null $total_ingress
     *
     * @property BankLoan $bank_loan
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @method static Builder|BankLoanItem newModelQuery()
     * @method static Builder|BankLoanItem newQuery()
     * @method static Builder|BankLoanItem query()
     */
    class BankLoanItem extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;

        protected $casts = [
            'bank_loan_id' => 'int',
            'total' => 'float',
            'total_interest' => 'float',
            'total_ingress' => 'float',
        ];

        protected $fillable = [
            'bank_loan_id',
            'description',
            'total_interest',
            'total_ingress',
            'total'
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
         * @return BankLoanItem
         */
        public function setBankLoanId(int $bank_loan_id): BankLoanItem
        {
            $this->bank_loan_id = $bank_loan_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getDescription(): string
        {
            return $this->description;
        }

        /**
         * @param string $description
         *
         * @return BankLoanItem
         */
        public function setDescription(string $description): BankLoanItem
        {
            $this->description = $description;
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
         * @return BankLoanItem
         */
        public function setTotal(float $total): BankLoanItem
        {
            $this->total = $total;
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
         * @return BankLoanItem
         */
        public function setBankLoan(BankLoan $bank_loan): BankLoanItem
        {
            $this->bank_loan = $bank_loan;
            return $this;
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
         * @return BankLoanItem
         */
        public function setTotalInterest(float $total_interest): BankLoanItem
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
         * @return BankLoanItem
         */
        public function setTotalIngress(float $total_ingress): BankLoanItem
        {
            $this->total_ingress = $total_ingress;
            return $this;
        }

    }

