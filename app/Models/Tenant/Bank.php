<?php

    namespace App\Models\Tenant;

    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;


    /**
     * Class App\Models\Tenant\Bank
     *
     * @property int                      $id
     * @property string                   $description
     * @property Carbon|null              $created_at
     * @property Carbon|null              $updated_at
     * @property bool                     $active
     * @property Collection|BankAccount[] $bank_accounts
     * @package App\Models\Tenant
     * @mixin ModelTenant
     * @method static Builder|Bank newModelQuery()
     * @method static Builder|Bank newQuery()
     * @method static Builder|Bank query()
     * @property-read int|null            $bank_accounts_count
     */
    class Bank extends ModelTenant
    {
        use UsesTenantConnection;

        protected $casts = [
            'active' => 'bool'
        ];
        protected $fillable = [
            'description',
            'active'
        ];

        // protected static function boot()
        // {
        //     parent::boot();

        //     static::addGlobalScope('active', function (Builder $builder) {
        //         $builder->where('active', 1);
        //     });
        // }


        /**
         * @return HasMany
         */
        public function bank_accounts()
        {
            return $this->hasMany(BankAccount::class);
        }
    }
