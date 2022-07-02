<?php


    namespace Modules\Account\Models;

    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;

    /**
     * Class AccountingLedgerCodeAccount
     *
     * @property int         $id
     * @property string      $code_account
     * @property string      $name
     * @property int|null    $disabled
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @mixin  ModelTenant
     * @package Modules\Account\Models
     */
    class AccountingLedgerCodeAccount extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'cat_accounting_ledger_code_account';
        protected $perPage = 25;

        protected $casts = [
            'disabled' => 'int'
        ];

        protected $fillable = [
            'code_account',
            'name',
            'disabled'
        ];

        public static function getNameByCodeAcount($code, $fallback = ''){
            $record = self::where('code_account',$code)->first();
            if($record!==null) {
                return $record->name;
            }
            return $fallback;

        }
    }
