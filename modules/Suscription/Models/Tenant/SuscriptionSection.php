<?php

    namespace Modules\Suscription\Models\Tenant;

    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class SuscriptionSection
     *
     * @property int    $id
     * @property string $name
     * @package Modules\Suscription\Models\Tenant
     * @method static Builder|SuscriptionSection newModelQuery()
     * @method static Builder|SuscriptionSection newQuery()
     * @method static Builder|SuscriptionSection query()
     * @mixin \Eloquent
     * @mixin ModelTenant
     */
    class SuscriptionSection extends ModelTenant
    {
        use UsesTenantConnection;
        protected $table = 'suscription_section';


        public $timestamps = false;

        protected $fillable = [
            'name'
        ];

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

    }
