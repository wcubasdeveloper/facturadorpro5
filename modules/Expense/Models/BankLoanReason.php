<?php



    namespace Modules\Expense\Models;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class BankLoanReason
     *
     * @property int    $id
     * @property string $description
     * @mixin ModelTenant
     * @package Modules\Expense\Models
     * @method static Builder|BankLoanReason newModelQuery()
     * @method static Builder|BankLoanReason newQuery()
     * @method static Builder|BankLoanReason query()
     */
    class BankLoanReason extends ModelTenant
    {
        use UsesTenantConnection;

        public $timestamps = false;

        protected $fillable = [
            'description'
        ];

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
         * @return BankLoanReason
         */
        public function setDescription(string $description): BankLoanReason
        {
            $this->description = $description;
            return $this;
        }

    }

