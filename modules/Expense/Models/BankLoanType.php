<?php



    namespace Modules\Expense\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class BankLoanType
     *
     * @property int $id
     * @property string $description
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @property Collection|BankLoan[] $bank_loans
     *
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @property-read int|null         $bank_loans_count
     * @method static Builder|BankLoanType newModelQuery()
     * @method static Builder|BankLoanType newQuery()
     * @method static Builder|BankLoanType query()
     */
    class BankLoanType extends ModelTenant
    {
        use UsesTenantConnection;


        protected $fillable = [
            'description'
        ];

        /**
         * @return HasMany
         */
        public function bank_loans()
        {
            return $this->hasMany(BankLoan::class);
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
         * @return BankLoanType
         */
        public function setDescription(string $description): BankLoanType
        {
            $this->description = $description;
            return $this;
        }

        /**
         * @return Collection|BankLoan[]
         */
        public function getBankLoans()
        {
            return $this->bank_loans;
        }

        /**
         * @param Collection|BankLoan[] $bank_loans
         *
         * @return BankLoanType
         */
        public function setBankLoans($bank_loans)
        {
            $this->bank_loans = $bank_loans;
            return $this;
        }

    }

