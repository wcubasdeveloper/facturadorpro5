<?php

    /**
     */

    namespace Modules\FullSuscription\Models\Tenant;


    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class CatPeriod
     *
     * @property int                                 $id
     * @property string                              $period
     * @property string                              $name
     * @property bool                                $active
     * @property Carbon|null                         $created_at
     * @property Carbon|null                         $updated_at
     * @property Collection|SuscriptionPlan[]        $suscription_plans
     * @property Collection|UserRelSuscriptionPlan[] $user_rel_suscription_plans
     * @package App\Models\Tenant\ModelTenant
     * @property-read int|null                       $suscription_plans_count
     * @property-read int|null                       $user_rel_suscription_plans_count
     * @method static Builder|CatPeriod newModelQuery()
     * @method static Builder|CatPeriod newQuery()
     * @method static Builder|CatPeriod query()
     * @mixin Eloquent
     */
    class CatPeriod extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'active' => 'bool'
        ];

        protected $fillable = [
            'period',
            'name',
            'active'
        ];

        /**
         * @return HasMany
         */
        public function suscription_plans()
        {
            return $this->hasMany(SuscriptionPlan::class);
        }

        /**
         * @return HasMany
         */
        public function user_rel_suscription_plans()
        {
            return $this->hasMany(UserRelSuscriptionPlan::class);
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }


        /**
         * @param string $name
         *
         * @return $this
         */
        public function setName(string $name): CatPeriod
        {
            $this->name = ucfirst(trim($name));
            return $this;
        }


        /**
         * @return bool
         */
        public function isActive(): bool
        {
            return $this->active;
        }

        /**
         * @param bool $active
         *
         * @return Task
         */
        public function setActive(bool $active): CatPeriod
        {
            $this->active = $active;
            return $this;
        }

    }
