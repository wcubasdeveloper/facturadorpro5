<?php


    namespace Modules\Suscription\Models\Tenant;


    use App\Models\Tenant\ModelTenant;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class SuscriptionGrade
     *
     * @property int    $id
     * @property string $name
     * @package Modules\Suscription\Models\Tenant
     * @method static Builder|SuscriptionGrade newModelQuery()
     * @method static Builder|SuscriptionGrade newQuery()
     * @method static Builder|SuscriptionGrade query()
     * @mixin ModelTenant
     * @mixin \Eloquent
     */
    class SuscriptionGrade extends ModelTenant
    {

        use UsesTenantConnection;
        protected $table = 'suscription_grade';
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
